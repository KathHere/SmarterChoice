<style type="text/css">/*
	.modal-dialog {
		width: 1200px;
	}*/

	.address-style {
		font-weight: bold;
	}
	.statement_letter_label_tag {
		font-weight: bold;
		margin-right: 6px;
	}
	.statement_letter_label_wrapper {
		margin-bottom: 20px;
	}

	.thead_style {
		font-weight: bold;
	}
</style>
<div style="padding-left: 10px; padding-right: 10px">
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Account Name:</label>
		<span>
			<select name="hospice_sorting_id" class="m-b form-control select2-ready" id="">
                <option value="all">Select Account</option>
                <?php
                    $hospices = get_hospices_v2($this->session->userdata('user_location'));
                    $companies = get_companies_v2($this->session->userdata('user_location'));
                    if (!empty($hospices)) {
                ?>
                        <!-- <optgroup label="Hospices"> -->
                    <?php
                        foreach ($hospices as $hospice) :
                            if ($hospice['hospiceID'] != 13) {
                    ?>
                                <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                    <?php echo $hospice['hospice_name']; ?>
                                </option>
                    <?php
                            }
                        endforeach;
                    ?>
                        <!-- </optgroup> -->
                <?php
                    }
                    if (!empty($companies)) {
                ?>
                        <!-- <optgroup label="Commercial Account"> -->
                <?php
                        foreach ($companies as $company):
                            if ($company['hospiceID'] != 13) {
                ?>
                                <option value="<?php echo $company['hospiceID']; ?>" <?php if ($hospice_selected == $company['hospiceID']) { echo 'selected'; } ?> >
                                    <?php echo $company['hospice_name']; ?>
                                </option>
                <?php
                            }
                        endforeach;
                ?>
                        <!-- </optgroup> -->

                        <option disabled="disabled">----------------------------------------</option>
                <?php
                    }
                    foreach ($hospices as $hospice) :
                        if ($hospice['hospiceID'] == 13) {
                ?>
                            <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                <?php echo $hospice['hospice_name']; ?>
                            </option>
            <?php
                        }
                    endforeach;
            ?>
            </select>
		</span>
	</div>
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Invoice Date:</label><span><input type="text" name="date_of_service" class="form-control datepicker" id="" placeholder="" style="margin-left:0px"></span>
	</div>
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Invoice Number:</label><span><input type="text" name="" class="form-control" id="" placeholder="" style="margin-left:0px" value="6284389" disabled></span>
	</div>
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Credit:</label><span><input type="text" name="" class="form-control" id="" placeholder="" style="margin-left:0px" value=""></span>
	</div>
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Amount Due:</label><span><input type="text" name="" class="form-control" id="" placeholder="" style="margin-left:0px" value=""></span>
	</div>
	<div class="statement_letter_label_wrapper">
		<label class="statement_letter_label_tag">Note:</label><span><textarea id="notes" class="form-control" style="width: 100%; height: 51px; border: none; padding: 10px; resize: none" placeholder="Enter note..."></textarea></span>
	</div>
	<hr />
	<button class="btn btn-md btn-success pull-right">Submit</button>
</div>

