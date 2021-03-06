<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The Original Code is UBMoD.
 *
 * The Initial Developer of the Original Code is Research Foundation of State
 * University of New York, on behalf of University at Buffalo.
 *
 * Portions created by the Initial Developer are Copyright (C) 2007 Research
 * Foundation of State University of New York, on behalf of University at
 * Buffalo.  All Rights Reserved.
 */

/**
 * Chart REST handler.
 *
 * @author Jeffrey T. Palmer <jtpalmer@ccr.buffalo.edu>
 * @version $Id$
 * @copyright Center for Computational Research, University at Buffalo, 2012
 * @package Ubmod
 */

/**
 * Chart REST Handler.
 *
 * @package Ubmod
 */
class Ubmod_Handler_Chart extends Ubmod_BaseHandler
{

  /**
   * Return help for the "cache" action.
   *
   * @return Ubmod_RestResponse
   */
  public function cacheHelp()
  {
    $desc = 'Cache the data needed to render a chart.  Returns the URL that'
      . ' can be used to access the chart.';
    $options = array(
      'model' => 'The data model of the chart ("user", "group", "wallTime",'
        . ' "waitTime", "tag").',
      'type' => 'The type of chart ("pie", "bar", "stackedArea").',
      'params' => 'The query parameters.',
    );
    return Ubmod_RestResponse::factory(array(
      'message' => $desc,
      'results' => $options,
    ));
  }

  /**
   * Cache chart data.
   *
   * @param array $arguments Request GET data.
   * @param array $postData Request POST data.
   *
   * @return Ubmod_RestResponse
   */
  public function cacheAction(array $arguments, array $postData = null)
  {
    global $BASE_URL;

    $params = Ubmod_Model_QueryParams::factory(
      json_decode($postData['params'], true)
    );

    $model = $postData['model'];
    $type  = $postData['type'];

    if (!$this->getRequest()->isAllowed('chart', $model)) {
      throw new Exception("Unauthorized access 'chart/$model'");
    }

    $method = 'get' . ucfirst($model) . ucfirst($type) . 'Data';

    try {
      $chart = array(
        'type' => $type,
        'data' => Ubmod_Model_Chart::$method($params),
      );
    } catch (Exception $e) {
      $msg = 'Failed to generate chart data: ' . $e->getMessage();
      return Ubmod_RestResponse::factory(array(
        'success' => false,
        'message' => $msg,
      ));
    }

    $id = Ubmod_Model_Chart::cacheSet($chart);

    return Ubmod_RestResponse::factory(array(
      'results' => array(
        'id'      => $id,
        'img_url' => $BASE_URL . '/chart/cached?id=' . $id . '&t=' . time(),
        'map_url' => $BASE_URL . '/chart/image-map?id=' . $id,
      ),
    ));
  }
}

