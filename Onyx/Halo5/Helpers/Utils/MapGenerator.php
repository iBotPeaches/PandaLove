<?php

namespace Onyx\Halo5\Helpers\Utils;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManager;
use Intervention\Image\Imagick\Shapes\CircleShape;
use Onyx\Halo5\Objects\Map;
use Onyx\Halo5\Objects\Match;

class MapGenerator
{
    /**
     * @param ImageManager $imageManager
     * @param array        $data
     *
     * @return mixed
     */
    public static function buildMap(ImageManager &$imageManager, array $data)
    {
        try {
            ini_set('max_execution_time', 300);
            $map = Map::where('uuid', $data['map_id'])->firstOrFail();
            $path = public_path('images/wireframes/');

            if (file_exists($path.$map->uuid.'.jpg')) {
                $img = $imageManager->make($path.$map->uuid.'.jpg');

                $matches = Match::where('map_id', $map->uuid)
                    ->with('events')
                    ->limit($data['num_games'])
                    ->get()
                    ->toArray();

                foreach ($matches as $match) {
                    foreach ($match['events'] as $event) {
                        $x = self::_adjust_point($data['x_orig'], $data['x_scale'], $event['killer_x']);
                        $y = self::_adjust_point($data['y_orig'], $data['y_scale'], $event['killer_y']);

                        $img->circle(4, $x, $y, function (CircleShape $draw) {
                            $draw->background('#000000');
                            $draw->border(1, '#000000');
                        });

                        $x = self::_adjust_point($data['x_orig'], $data['x_scale'], $event['victim_x']);
                        $y = self::_adjust_point($data['y_orig'], $data['y_scale'], $event['victim_y']);

                        $img->circle(4, $x, $y, function (CircleShape $draw) {
                            $draw->background('#000000');
                            $draw->border(1, '#000000');
                        });
                    }
                }

                $ret['error'] = false;
                $ret['image'] = $img->encode('data-url');
            } else {
                $ret['error'] = true;
                $ret['message'] = 'We do not have a wireframe for this map. Sorry.';
            }
        } catch (ModelNotFoundException $e) {
            $ret['error'] = true;
            $ret['message'] = 'The map could not be found.';
        }

        return $ret;
    }

    /**
     * @param $orig int
     * @param $scale int
     * @param $meter int
     *
     * @return mixed
     */
    private static function _adjust_point($orig, $scale, $meter)
    {
        return $orig + ($scale * $meter);
    }
}
