<?php
/**
 * CPU Consumption controller.
 *
 * @author Jeffrey T. Palmer <jtpalmer@ccr.buffalo.edu>
 * @version $Id$
 * @copyright Center for Computational Research, University at Buffalo, 2011
 * @package UBMoD
 */

/**
 * CPU Consumption controller.
 *
 * @package UBMoD
 */
class UBMoD_Controller_CpuConsumption extends UBMoD_Controller_Base
{

  /**
   * Execute the index action.
   *
   * @return void
   */
  public function executeIndex()
  {

  }

  /**
   * Execute the chart action.
   *
   * @return void
   */
  public function executeChart()
  {
    $post = $this->getPostData();
    $this->chart
      = '/chart/cpu-consumption?interval_id=' . $post['interval_id']
      . '&amp;cluster_id=' . $post['cluster_id'] . '&amp;t=' . time();
  }
}
