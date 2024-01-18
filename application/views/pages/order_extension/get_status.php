<?php 
    $activity_counts = get_count_status_new_v2($this->session->userdata('user_location'));
    $status_counts = array(
              "En route"    => $activity_counts[1]['total'],
              "On Hold"     => $activity_counts[3]['total'],
              "Pending"     => $activity_counts[0]['total'],
              "Rescheduled" => $activity_counts[2]['total']
            );
    $label = array("btn-primary","btn-warning","btn-success","btn-danger");
    $index = 0;
?>
<ul class="status-count list-inline">
      <?php 
          foreach($status_counts as $key=>$value):
            if($value>0): 
      ?>
              <li class="pull-left">
                <span class="pos_counter" data-id="<?php echo $key; ?>" style="cursor:pointer;"><?php echo $key; ?></span>&nbsp;
                <span><strong><?php echo $value; ?></strong></span>
              </li>
      <?php
            endif; 
          $index++;
          endforeach; 
      ?>
</ul>