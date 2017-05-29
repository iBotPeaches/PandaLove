<?php

namespace PandaLove\Http\Controllers\Backstage;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManager;
use Onyx\Account;
use Onyx\Halo5\Client as Halo5Client;
use Onyx\Halo5\Helpers\Utils\MapGenerator;
use Onyx\Halo5\Objects\Map;
use PandaLove\Commands\UpdateHalo5Account;
use PandaLove\Http\Controllers\Controller;
use PandaLove\Http\Requests\AddHalo5GamertagRequest;
use PandaLove\Http\Requests\Halo5MapGeneratorRequest;

class Halo5Controller extends Controller
{
    /**
     * @var ImageManager
     */
    protected $image;

    /**
     * Halo5Controller constructor.
     *
     * @param Guard        $auth
     * @param ImageManager $image
     */
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
            'maps'     => $maps,
        ]);
    }

    public function getMaps()
    {
        $maps = Map::where('game_modes', '!=', '["Campaign"]')
            ->orderBy('name')
            ->get();

        return view('backstage.halo5.maps', [
            'maps' => $maps,
        ]);
    }

    public function getMap($id)
    {
        try {
            $map = Map::where('contentId', $id)->firstOrFail();

            return \Response::json([
                'error' => false,
                'map'   => $map,
            ]);
        } catch (ModelNotFoundException $ex) {
            return \Response::json([
                'error'   => true,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function postMaps(Halo5MapGeneratorRequest $request)
    {
        $ret = [];
        $data = $request->request->all();

        if ($request->request->get('type') == 'generate') {
            $ret = MapGenerator::buildMap($this->image, $data);
        } else {
            try {
                $map = Map::where('uuid', $data['map_id'])->firstOrFail();
                $map->x_orig = $data['x_orig'];
                $map->y_orig = $data['y_orig'];
                $map->x_scale = $data['x_scale'];
                $map->y_scale = $data['y_scale'];
                $map->save();

                $ret['error'] = false;
                $ret['message'] = 'Scale saved successfully';
            } catch (ModelNotFoundException $e) {
                $ret['error'] = true;
                $ret['message'] = 'The map specified could not be found.';
            }
        }

        return \Response::json([
            $ret,
        ]);
    }

    public function postAddHalo5Gamertag(AddHalo5GamertagRequest $request)
    {
        $client = new Halo5Client();
        $account = $client->getAccountByGamertag($request->request->get('gamertag'));

        $this->dispatch(new UpdateHalo5Account($account));

        return \Redirect::action('Halo5\ProfileController@index', [$account->seo]);
    }
}
