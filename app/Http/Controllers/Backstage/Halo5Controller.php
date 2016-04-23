<?php namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Account;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\Map;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests;
use PandaLove\Http\Requests\AddHalo5GamertagRequest;
use PandaLove\Http\Requests\Halo5MapGeneratorRequest;
use Intervention\Image\ImageManager;

class Halo5Controller extends Controller
{

    public function __construct(Guard $auth, ImageManager $image)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('auth.admin');
        $this->image = $image;
    }

    public function getIndex()
    {
        $accounts = Account::with('h5', 'user')
            ->whereHas('h5', function ($query) {
                $query->where('Xp', '!=', 0);
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $maps = \DB::table('halo5_matches')
            ->leftJoin('halo5_maps', 'halo5_matches.map_id', '=', 'halo5_maps.contentId')
            ->select(\DB::raw('count(*) as total'), 'halo5_maps.name')
            ->groupBy('map_variant')
            ->orderBy('total', 'DESC')
            ->get();

        return view('backstage.halo5.index', [
            'accounts' => $accounts,
            'maps' => $maps,
        ]);
    }

    public function getMaps()
    {
        $maps = Map::where('game_modes', '!=', '["Campaign"]')
            ->orderBy('name')
            ->get();

        return view('backstage.halo5.maps', [
            'maps' => $maps
        ]);
    }

    public function postMaps(Halo5MapGeneratorRequest $request)
    {
        $ret = [];
        if ($request->request->get('type') == "generate") {
            // Generate map
            try {
                ini_set('max_execution_time', 300);
                $map = Map::where('uuid', $request->request->get('map_id'))->firstOrFail();

                $path = public_path('images/wireframes/');
                if (file_exists($path . $map->uuid . '.jpg')) {
                    $img = $this->image->make($path . $map->uuid . '.jpg');

                    $matches = Match::where('map_id', $map->uuid)
                        ->with('events')
                        ->limit($request->request->get('num_games'))
                        ->get()
                        ->toArray();

                    foreach ($matches as $match) {
                        foreach ($match['events'] as $event) {
                            $x = $this->get_adj_point($request->request->get('x_orig'), $request->request->get('x_scale'), $event['killer_x']);
                            $y = $this->get_adj_point($request->request->get('y_orig'), $request->request->get('y_scale'), $event['killer_y']);

                            $img->circle(4, $x, $y, function($draw) {
                                $draw->background('#000000');
                                $draw->border(1, '#000000');
                            });

                            $x = $this->get_adj_point($request->request->get('x_orig'), $request->request->get('x_scale'), $event['victim_x']);
                            $y = $this->get_adj_point($request->request->get('y_orig'), $request->request->get('y_scale'), $event['victim_y']);

                            $img->circle(4, $x, $y, function($draw) {
                                $draw->background('#000000');
                                $draw->border(1, '#000000');
                            });
                        }
                    }

                    $ret['error'] = false;
                    $ret['image'] = $img->encode('data-url');
                } else {
                    $ret['error'] = true;
                    $ret['message'] = "We do not have a wireframe for this map. Sorry.";
                }
            } catch (ModelNotFoundException $e) {
                $ret['error'] = true;
                $ret['message'] = "The map could not be found.";
            }
        } else {
            // Save scaling
            try
            {
                $map = Map::where('uuid', $request->request->get('map_id'))->firstOrFail();
                $map->x_orig = $request->request->get('x_orig');
                $map->y_orig = $request->request->get('y_orig');
                $map->x_scale = $request->request->get('x_scale');
                $map->y_scale = $request->request->get('y_scale');
                $map->save();

                $ret['error'] = false;
                $ret['message'] = "Scale saved successfully";
            }
            catch (ModelNotFoundException $e)
            {
                $ret['error'] = true;
                $ret['message'] = "The map specified could not be found.";
            }
        }

        return \Response::json([
            $ret
        ]);
    }

    public function postAddHalo5Gamertag(AddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }

    private function get_adj_point($orig, $scale, $meter)
    {
        return $orig + ($scale * $meter);
    }
}
