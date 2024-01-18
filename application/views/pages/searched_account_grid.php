<div class="bg-light lter b-b wrapper-md">

<?php
  if (count($orders) > 1) :?>
  <h1 class="m-n font-thin h3">View All Accounts (Accounts: <?php echo $counting; ?>)</h1>
<?php else:?>
  <h1 class="m-n font-thin h3">View Account (Account: <?php echo $counting; ?>)</h1>
<?php endif;?> 

</div>

<div class="wrapper-md" id="wrapper_account">
<?php 
  if (empty($accounts)) :
        ?>
      <img src="<?php echo base_url(); ?>assets/img/empty_folder.png" />
      <h4>No Records Found.</h4>
<?php
  endif;
?>

  <div class="row patient-list" id="account_list_container">
    <?php 
      if (!empty($accounts)) {
        $temp_accounts = [];
        if (isset($accounts['result'])) {
          $temp_accounts = $accounts['result'];
          echo '<input type="hidden" id="is_searched" value="0" />';
        } else {
          $temp_accounts = $accounts;
          echo '<input type="hidden" id="is_searched" value="1" />';
        }
        foreach ($temp_accounts as $account) :
          ?> 
          <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="panel wrapper">
              <div class="icon-container bg-info">
                <a href="<?php echo base_url('billing_statement/statement_bill/'.$account['hospiceID'].'/'); ?>" class="link-record">
                  <button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">                   
                    <i class="fa fa-hospital-o" style="font-size:65px;"></i>
                  </button>
                </a>
              </div>

              <h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">ACCT NO. <?php echo $account['hospice_account_number']; ?></h4>
              <span class="patient-name"><?php echo $account['hospice_name']; ?> </span>
               
            </div>
          </div> <!-- .col-xs-6 col-sm-4 col-md-3 -->
      <?php 
      endforeach;
    }
      ?>
      

    <input type="hidden" id="total_pages" value="<?php echo $total_pages; ?>" />
  </div> <!--.row patient-list -->
    <div class="text-center" id="load_more_content" style="display:none">
      <button class="btn btn-info" style="margin-top:90px;" id="load_more_account" data-current-count="<?php echo $accounts['limit'];?>" data-total-count="<?php echo $accounts['totalAccountCount'];?>" style="text-align:center;">Load More</button>
    </div>
</div>

<input type="hidden" class="total_search_count" value="<?php echo $counting; ?>">
<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
  var limit = 20;
  $(document).ready(function(){
    var is_searched = $('#is_searched').val();

    if (is_searched == 0) {
      //FOR AUTOLOAD IF THE DOCUMENT IS READY
      setTimeout(function(){
        $('#load_more_account').click();
      },1000);
    }
  });

  $('#wrapper_account').on('click', '#load_more_account', function(e)
  {
    var currentCount = $(this).attr("data-current-count");
    console.log(currentCount);
    var totalCount = $(this).attr("data-total-count");
    var account_list = $('#account_list_container');
    account_list.append('<p class="text-center" id="load_more"><i class="fa fa-spinner fa-spin fa-2x"></i></p>');

    $('#load_more_account').remove();

    setTimeout(function(){
     $.get(base_url+'billing_statement/billing_list_load_more/'+currentCount+"/"+limit, function(response){
      var jsonparse = JSON.parse(response);
      console.log(jsonparse.result);
      if(jsonparse.result != null)
      {
        
        var final_html = "";
        for (var i = 0; i < jsonparse.result.length; i++)
        {
            
            final_html += '<div class="col-xs-6 col-sm-4 col-md-3">';
            final_html += '<div class="panel wrapper">';
            final_html += '<div class="icon-container bg-info">';
            final_html += '<a href="'+base_url+'billing_statement/statement_bill/'+jsonparse.result[i].hospiceID+'" class="link-record">';
            final_html += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">';
            final_html += '<i class="fa fa-hospital-o" style="font-size:65px;"></i>';
            final_html += '</button>';
            final_html += '</a>';
            final_html += '</div>';
            final_html += '<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">ACCT NO. '+jsonparse.result[i].hospice_account_number+'</h4>';
            final_html += '<span class="patient-name">'+jsonparse.result[i].hospice_name+' </span>';
            final_html += '</div>';
            final_html += '</div>';
        }

        
        $('#load_more').remove();
        account_list.append(final_html);

        if((parseInt(jsonparse.totalCount) + parseInt(currentCount)) < jsonparse.totalAccountCount)
        {
          $('#load_more_content').append('<button class="btn btn-info" style="margin-top:90px;" id="load_more_account" data-current-count="'+(parseInt(jsonparse.totalCount) + parseInt(currentCount))+'" data-total-count="'+jsonparse.totalAccountCount+'" style="text-align:center;">Load More</button>');
          $('#load_more_account').click();
        }
      }
    });
    },1500);
    
    
  });
  
 
  
</script>

