<style type="text/css">
	
	.item_usage_patient_list_td, .item_usage_patient_list_th
	{
		text-align: center;
	}
	.patientlists
	{
		margin-top:10px !important;
		border-right:1px solid rgba(72, 70, 70, 0.11)!important;
		border-left:1px solid rgba(72, 70, 70, 0.11)!important;
		border-bottom:1px solid rgba(72, 70, 70, 0.11)!important;
		border-top:1px solid rgba(72, 70, 70, 0.11)!important;
	}
	.item_usage_patient_list_panel_body
	{
		padding-left:10px;
		padding-right:10px;
		margin-bottom:5px !important;
		margin-top:-5px !important;
	}
</style>

<div class="clearfix">

	<div class="panel-body item_usage_patient_list_panel_body">
		<table class="table patientlists">
			<thead style="background-color:rgba(97, 101, 115, 0.05);" >
				<th class="item_usage_patient_list_th" style="width:45%;">Patient Name</th>
				<th class="item_usage_patient_list_th" style="width:30%;">MR#</th>
				<th class="item_usage_patient_list_th" style="width:25%;"></th>
			</thead>
			<?php 
				if(!empty($patients)):   
					foreach($patients as $patient): ?>
						<tr>
							<td class="item_usage_patient_list_td" ><?php echo ucfirst($patient['p_lname']); ?>, <?php echo ucfirst($patient['p_fname']); ?></td>
							<td class="item_usage_patient_list_td" ><?php echo $patient['medical_record_id']; ?></td>
							<td class="item_usage_patient_list_td" ><a href="<?php echo base_url("order/patient_profile/{$patient['medical_record_id']}/{$patient['ordered_by']}");?>" target="_blank"><i class="fa fa-external-link"></i></a></td>
						</tr>
			<?php 
					endforeach; 
				endif; 
			?>
		</table>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('.patientlists').DataTable();

	});
</script>