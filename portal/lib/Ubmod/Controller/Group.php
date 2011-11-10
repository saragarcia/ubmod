<?php
/*
 * The contents of this file are subject to the University at Buffalo Public
 * License Version 1.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.ccr.buffalo.edu/licenses/ubpl.txt
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 *
 * The Original Code is UBMoD.
 *
 * The Initial Developer of the Original Code is Research Foundation of State
 * University of New York, on behalf of University at Buffalo.
 *
 * Portions created by the Initial Developer are Copyright (C) 2007 Research
 * Foundation of State University of New York, on behalf of University at
 * Buffalo.  All Rights Reserved.
 *
 * Alternatively, the contents of this file may be used under the terms of
 * either the GNU General Public License Version 2 (the "GPL"), or the GNU
 * Lesser General Public License Version 2.1 (the "LGPL"), in which case the
 * provisions of the GPL or the LGPL are applicable instead of those above. If
 * you wish to allow use of your version of this file only under the terms of
 * either the GPL or the LGPL, and not to allow others to use your version of
 * this file under the terms of the UBPL, indicate your decision by deleting
 * the provisions above and replace them with the notice and other provisions
 * required by the GPL or the LGPL. If you do not delete the provisions above,
 * a recipient may use your version of this file under the terms of any one of
 * the UBPL, the GPL or the LGPL.
 */

/**
 * Group controller.
 *
 * @author Jeffrey T. Palmer <jtpalmer@ccr.buffalo.edu>
 * @version $Id$
 * @copyright Center for Computational Research, University at Buffalo, 2011
 * @package Ubmod
 */

/**
 * Group controller.
 *
 * @package Ubmod
 */
class Ubmod_Controller_Group extends Ubmod_BaseController
{

  /**
   * Execute the "index" action.
   *
   * @return void
   */
  public function executeIndex()
  {

  }

  /**
   * Execute the "details" action.
   *
   * @return void
   */
  public function executeDetails()
  {
    $params = Ubmod_Model_QueryParams::factory($this->getPostData());

    $this->interval = Ubmod_Model_TimeInterval::getByParams($params);
    $this->group    = Ubmod_Model_Job::getEntity('group', $params);
    $queryString    = Ubmod_Model_Chart::getQueryString($params);

    $this->pieChart  = '/chart/user-pie?'  . $queryString;
    $this->barChart  = '/chart/user-bar?'  . $queryString;
    $this->areaChart = '/chart/user-area?' . $queryString;
  }

  /**
   * Execute the "csv" action.
   *
   * @return void
   */
  public function executeCsv()
  {
    $params = Ubmod_Model_QueryParams::factory($this->getGetData());
    $users = Ubmod_Model_Job::getActivityList($params);

    header('Content-type: text/csv');
    header('Content-disposition: attachment; filename=groups.csv');

    $columns = array(
      'group_name' => 'Group',
      'jobs'       => '# Jobs',
      'avg_cpus'   => 'Avg. Job Size (cpus)',
      'avg_wait'   => 'Avg. Wait Time (h)',
      'wallt'      => 'Wall Time (d)',
      'avg_mem'    => 'Avg. Mem (MB)',
    );

    echo implode("\t", array_values($columns)), "\n";

    $keys = array_keys($columns);

    foreach ($users as $user) {
      $map = function ($key) use($user) { return $user[$key]; };
      $values = array_map($map, $keys);
      echo implode("\t", $values), "\n";
    }

    exit();
  }
}
