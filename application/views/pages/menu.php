<style type="text/css">

.app{
/*background: url(assets/img/c3.jpg) no-repeat center center fixed;
 -webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;*/

background: url(assets/img/c3.jpg) no-repeat top;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
background-repeat: no-repeat;

}

.app-content-body
{
  /*background-color:#3a3f51;*/
  /*background: url(../assets/img/c3.jpg) center center fixed;*/
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.app.app-header-fixed{
  /*background-position: 200px;*/
}
@media(max-width: 768px){
  .app.app-header-fixed{
    background-position: 0 !important;
  }
  .app
  {
    background: linear-gradient(to right, #c7c2b6 , #9b978c);
  }
}

.icon-container{
  margin-bottom: 50px;
}
.btn-menu{
width: 160px !important;
height: 160px !important;
background: transparent;
border: 2px solid #fff;
border-radius: 144px;
}

.anotherX{
margin-top: 255px;
}

.col-sm-2XXX{
width: 21.666667% !important;
}

.menu-title{
font-size: 18px;
margin-top: 12px;
text-align:center;
color:#fff;
}

.btn-info:hover{
color: #ffffff !important;
background-color: rgba(255,255,255, 0.2)!important;
border: 3px solid #fff !important;
transition: background 200ms ease-out;
-webkit-transition: background 0.2s ease-in-out;
-moz-transition: background 0.2s ease-in-out;
-o-transition: background 0.2s ease-in-out;
transition: background 0.2s ease-in-out;
}

.coming_soon_bg:hover
{

  z-index: 999999 !important;
  background-repeat: no-repeat;
}

</style>

<script type="text/javascript">

  // $(document).ready(function(){
    // $('.folded-portion').click(function(){
        // if($('#app').hasClass('app-aside-folded'))
        // {
           // $('#app').css('backgroundPosition','200px');
       // $('.collapse-brand-img').css('display','none');
       // $('.uncollapse-brand-img').css('display','block');
        // }
        // else
        // {
           // $('#app').css('backgroundPosition','60px 50%');
       // $('.collapse-brand-img').css('display','block');
       // $('.uncollapse-brand-img').css('display','none');
        // }
    // });
  // });

</script>

<?php
  $hospice_id = $this->session->userdata("group_id");
  $user_type = $this->session->userdata("account_type");
?>

<input type="hidden" id="is_first_loggedin" value="<?php echo $this->session->userdata('is_first_loggedin') ?>" />
<input type="hidden" id="is_changed_password" value="<?php echo $this->session->userdata('is_changed_password') ?>" />
<!-- COPY the content from "tpl/" -->
  <div class="wrapper-md">
    <div class="row mt20">
      <div class="col-sm-12 mt15">
        <?php if ($user_type != "dispatch") { ?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <div class="icon-container ">
            <center>
            <?php if($user_type == "hospice_admin" || $user_type == "hospice_user") :?>
              <a href="<?php echo base_url("order/create_order")."/".$hospice_id ?>">
                <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-notebook" style="font-size:38px ;"></i></button>
              </a>
            <?php else:?>
            <a href="<?php echo base_url("order/create_order") ?>">
              <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-notebook" style="font-size:38px ;"></i></button>
            </a>
            <?php endif;?>
            </center>
            <p class="menu-title" >Create New Customer</p>
          </div>
        </div>
        <?php } ?>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <div class="icon-container ">
              <center>
                  <a href="<?php echo base_url()?>order/search">
                    <button class="btn btn-sm btn-icon btn-info btn-menu"><i class=" icon-users" style="font-size:38px ;"></i></button>
                  </a>
              </center>
                <p class="menu-title">Customer Search</p>
          </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <div class="icon-container ">
            <center>
              <a href="<?php echo base_url()?>order/order_list">
                <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-folder-alt" style="font-size:38px ;"></i></button>
              </a>
            </center>
            <p class="menu-title">Customer Order Status</p>
          </div>
        </div>

        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

            <div class="icon-container">
                  <center>
                  <a href="<?php echo base_url() ?>order/tracking">
                    <button class="btn btn-sm btn-icon btn-info btn-menu coming_soon_bg">
                      <i class="icon-docs" style="font-size:38px ;"></i>
                    </button>
                  </a>
                </center>
                  <p class="menu-title " >DME Tracking</p>
            </div>
        </div>
        <?php endif ;?>


        <!-- another row -->
        <?php if ($user_type != "dispatch" && $user_type != "rt" && $user_type != "hospice_user") { ?>
        <div class="another clearfix">
          <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

            <div class="icon-container ">
              <center>
                <a href="<?php echo base_url()?>equipment/all_equipments_by_hospice">
                  <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-social-dropbox" style="font-size:38px ;"></i></button>
                </a>
              </center>
              <p class="menu-title">Fee Schedule</p>
            </div>

          </div>
        </div>
        <?php } ?>
      <!-- <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

          <div class="icon-container ">
              <center>
                <a href="<?php echo base_url()?>report">
                  <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-list" style="font-size:38px ;"></i></button>
                </a>
              </center>
                <p class="menu-title">Daily Activity Status</p>
          </div>

      </div> -->


    <!-- <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

          <div class="icon-container ">
              <center>
                <a href="<?php echo base_url()?>gallery/beds">
                  <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-camera" style="font-size:38px ;"></i></button>
                </a>
              </center>
                <p class="menu-title">Item Photo Gallery</p>
          </div>

      </div> -->




      <!--  <div class="col-sm-2">

          <div class="icon-container data_tooltip" title="COMING SOON">
                 <center>
                 <a href="#">
                  <button class="btn btn-sm btn-icon btn-info btn-menu"><i class=" icon-bell" style="font-size:38px ;"></i></button>
                 </a>
              </center>
                <p class="menu-title">Notifications</p>
          </div>

      </div>

       <div class="col-sm-2">

          <div class="icon-container data_tooltip" title="COMING SOON">
              <center>
                <a href="#">
                    <button class="btn btn-sm btn-icon btn-info btn-menu"><i class="icon-wrench" style="font-size:38px ;"></i></button>
                </a>
              </center>
                <p class="menu-title">Settings</p>
          </div>

      </div> -->
      </div>

    </div>
  </div>
<!-- PASTE above -->


<div class="modal fade" id="modal_for_hippa_policy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="  height: 600px;overflow: scroll;overflow-x: hidden;">
      <div class="modal-header">
        <h4 class="modal-title">HEALTH INSURANCE PORTABILITY AND ACCOUNTABILITY ACT OF 1996</h4>
      </div>

      <div class="modal-body">
          <p>Public Law 104-191</p>
          <p>104th Congress</p>

          <h4 style="text-align:center">An Act</h4>
          <p style="text-align:justify">To amend the Internal Revenue Code of 1986 to improve portability and continuity of
              health insurance coverage in the group and individual markets, to combat waste,
              fraud, and abuse in health insurance and health care delivery, to promote the use of
              medical savings accounts, to improve access to long-term care services and
              coverage, to simplify the administration of health insurance, and for other purposes.
              Be it enacted by the Senate and House of Representatives of the United States of
              America in Congress assembled,
          </p>

          <h4>SECTION 1. SHORT TITLE; TABLE OF CONTENTS.</h4>
          <p style="text-align:justify">(a) SHORT TITLE.--This Act may be cited as the "Health Insurance Portability and
              Accountability Act of 1996".
          </p>

          <p style="text-align:justify">(b) TABLE OF CONTENTS.--The table of contents of this Act is as follows:</p>

          <h4>TITLE I--HEALTH CARE ACCESS, PORTABILITY, AND RENEWABILITY</h4>
          <h4>TITLE II--PREVENTING HEALTH CARE FRAUD AND ABUSE; ADMINISTRATIVE SIMPLIFICATION; MEDICAL LIABILITY REFORM</h4>


          <h4 style="text-align:center">An Act</h4>
          <p>• Sec. 261. Purpose.<p>
          <p>• Sec. 262. Administrative simplification.</p>

          <h4 style="text-align:center">"Part C--Administrative Simplification</h4>
          <p>• "Sec. 1171. Definitions.</p>
          <p>• "Sec. 1172. General requirements for adoption of standards.</p>
          <p>• "Sec. 1173. Standards for information transactions and data elements.</p>
          <p>• "Sec. 1174. Timetables for adoption of standards.</p>
          <p>• "Sec. 1175. Requirements.</p>
          <p>• "Sec. 1176. General penalty for failure to comply with requirements and standards.</p>
          <p>• "Sec. 1177. Wrongful disclosure of individually identifiable health information.</p>
          <p>• "Sec. 1178. Effect on State law.</p>
          <p>• "Sec. 1179. Processing payment transactions.".</p>
          <p>Sec. 263. Changes in membership and duties of National Committee on Vital and Health Statistics.</p>
          <p>Sec. 264. Recommendations with respect to privacy of certain health information.</p>

          <h4 style="text-align:center">Subtitle F--Administrative Simplification</h4>
          <h4>SEC. 261. PURPOSE.</h4>
          <p style="text-align:justify">It is the purpose of this subtitle to improve the Medicare program under title XVIII of the Social Security Act, the medicaid program under title XIX of such Act, and the efficiency and effectiveness of the health care system, by encouraging the development of a health information system through the establishment of standards and requirements for the electronic transmission of certain health information.</p>

          <h4>SEC. 262. ADMINISTRATIVE SIMPLIFICATION.</h4>
          <p style="text-align:justify">(a) IN GENERAL.--Title XI (42 U.S.C. 1301 et seq.) is amended by adding at the end the following:</p>

          <h4 style="text-align:center">"PART C--ADMINISTRATIVE SIMPLIFICATION</h4>
          <h4 style="text-align:center">"DEFINITIONS</h4>

          <h4>"SEC. 1171. For purposes of this part:</h4>
          <p>"(1) CODE SET.--The term 'code set' means any set of codes used for encoding data elements, such as tables of terms, medical concepts, medical diagnostic codes, or medical procedure codes.</p>
          <p>"(2) HEALTH CARE CLEARINGHOUSE.--The term 'health care clearinghouse' means a public or private entity that processes or facilitates the processing of nonstandard data elements of health information into standard data elements.</p>
          <p>"(3) HEALTH CARE PROVIDER.--The term 'health care provider' includes a provider ofservices (as defined in section 1861(u)), a provider of medical or other health services (as defined in section 1861(s)), and any other person furnishing health care services or supplies.</p>
          <p>"(4) HEALTH INFORMATION.--The term 'health information' means any information, whether oral or recorded in any form or medium, that--</p>
          <p>"(A) is created or received by a health care provider, health plan, public healthauthority, employer, life insurer, school or university, or health care clearinghouse; and</p>
          <p>"(B) relates to the past, present, or future physical or mental health or condition ofan individual, the provision of health care to an individual, or the past, present, or future payment for the provision of health care to an individual.</p>
          <p>"(5) HEALTH PLAN.--The term 'health plan' means an individual or group plan thatprovides, or pays the cost of, medical care (as such term is defined in section 2791 of the Public Health Service Act). Such term includes the following, and any combination thereof:</p>
          <p>"(A) A group health plan (as defined in section 2791(a) of the Public Health Service Act), but only if the plan--</p>
          <p>"(i) has 50 or more participants (as defined in section 3(7) of the Employee Retirement Income Security Act of 1974); or</p>
          <p>"(ii) is administered by an entity other than the employer who established andmaintains the plan.</p>
          <p>"(B) A health insurance issuer (as defined in section 2791(b) of the Public Health Service Act).</p>
          <p>"(C) A health maintenance organization (as defined in section 2791(b) of the Public Health Service Act).</p>
          <p>"(D) Part A or part B of the Medicare program under title XVIII.</p>
          <p>"(E) The medicaid program under title XIX.</p>
          <p>"(F) A Medicare supplemental policy (as defined in section 1882(g)(1)).</p>
          <p>"(G) A long-term care policy, including a nursing home fixed indemnity policy (unless the Secretary determines that such a policy does not provide sufficiently comprehensive coverage of a benefit so that the policy should be treated as a health plan).</p>
          <p>"(H) An employee welfare benefit plan or any other arrangement which is establishedor maintained for the purpose of offering or providing health benefits to the employees of 2 or more employers.</p>
          <p>"(I) The health care program for active military personnel under title 10, UnitedStates Code.</p>
          <p>"(J) The veterans health care program under chapter 17 of title 38, United States Code.</p>
          <p>"(K) The Civilian Health and Medical Program of the Uniformed Services (CHAMPUS),as defined in section 1072(4) of title 10, United States Code.</p>
          <p>"(L) The Indian health service program under the Indian Health Care Improvement Act (25 U.S.C. 1601 et seq.).</p>
          <p>"(M) The Federal Employees Health Benefit Plan under chapter 89 of title 5, United States Code.</p>
          <p>"(6) INDIVIDUALLY IDENTIFIABLE HEALTH INFORMATION.--The term 'individually identifiable health information' means any information, including demographic information collected from an individual, that--</p>
          <p>"(A) is created or received by a health care provider, health plan, employer, or health care clearinghouse; and</p>
          <p>"(B) relates to the past, present, or future physical or mental health or condition of an individual, the provision of health care to an individual, or the past, present, or future payment for the provision of health care to an individual, and--</p>
          <p>"(i) identifies the individual; or</p>
          <p>"(ii) with respect to which there is a reasonable basis to believe that the information can be used to identify the individual.</p>
          <p>"(7) STANDARD.--The term 'standard', when used with reference to a data elementof health information or a transaction referred to in section 1173(a)(1), means any such data element or transaction that meets each of the standards and implementation specifications adopted or established by the Secretary with respect to the data element or transaction under sections 1172 through 1174.</p>
          <p>"(8) STANDARD SETTING ORGANIZATION.--The term 'standard setting organization' means a standard setting organization accredited by the American National Standards Institute, including the National Council for Prescription Drug Programs, that develops standards for information transactions, data elements, or any other standard that is necessary to, or will facilitate, the implementation of this part.</p>


          <p class="pull-right" style="margin-top:-10px">
            <input type="checkbox" class="pull-left" id="check_hippa_policy" style="margin-right:5px" data-user-id="<?php echo $this->session->userdata('userID') ?>" /> <span> I agree to the terms and conditions</span>
          </p>
      </div>


      <div class="modal-footer">

      <?php echo form_open("",array("id"=>"update_user_hippa")) ?>

        <input type="hidden" id="hdn_first_loggedin" name="first_loggedin" value="" />
        <input type="hidden" id="hdn_changed_password" name="changed_password" value="" />
        <button type="button" class="btn btn-primary" id="agreed_to_hippa_policy" data-user-id="<?php echo $this->session->userdata('userID') ?>" disabled>Proceed</button>


      <?php echo form_close() ?>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->