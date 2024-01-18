<div class="panel-body" style="padding: 0px;">
	<table class="table table-hover bg-white b-a datatable_table_statement_draft">
		<thead >
			<th style="text-align: center;">Date</th>
			<th style="text-align: center;">Payment Amount</th>
            <!-- <th style="text-align: center;">Actions</th> -->
		</thead>
		<tbody>
            <?php
            foreach($pending_payments as $value) {
            ?>
            <tr style="text-align: center">
                <td><?php echo date("m/d/Y", strtotime($value['payment_date'])); ?></td>
                <td><?php echo number_format((float)$value['payment_amount'], 2, '.', ''); ?></td>
            </tr>
            <?php
            }
            ?>
            
            <!-- <tr style="text-align: center">
                <td>06/28/2019</td>
                <td>2900.00</td>
            </tr>
            <tr style="text-align: center">
                <td>06/28/2019</td>
                <td>2900.00</td>
            </tr> -->
		</tbody>
	</table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datatable_table_statement_draft').DataTable( {
            "order": [[ 1, "asc" ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                }
            ]
        } );
    });
</script>