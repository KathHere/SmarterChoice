<div class="hbox hbox-auto-xs hbox-auto-sm" ng-init="
    app.settings.asideFolded = false; 
    app.settings.asideDock = false;
  ">
  <!-- main -->
  <div class="col">
    <!-- main header -->
    <div class="bg-light lter b-b wrapper-md">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <h1 class="m-n font-thin h3 text-black">DME DASHBOARD</h1>
          <small class="text-muted">Welcome to DME Software application</small>
        </div>
        <div class="col-sm-6 text-right hidden-xs">
          <div class="inline m-r text-left">
            <div class="m-b-xs"> $1,290 <span class="text-muted">Sales Yesterday</span></div>
            <div ng-init="data1=[ 106,108,110,105,110,109,105,104,107,109,105,100,105,102,101,99,98 ]" 
              ui-jq="sparkline" 
              ui-options="{{data1}}, {type:'bar', height:20, barWidth:5, barSpacing:1, barColor:'#dce5ec'}" 
              class="sparkline inline">loading...
            </div>
          </div>
          <div class="inline text-left">
            <div class="m-b-xs">$30,000 <span class="text-muted">revenue</span></div>
            <div ng-init="data2=[ 105,102,106,107,105,104,101,99,98,109,105,100,108,110,105,110,109 ]" 
              ui-jq="sparkline" 
              ui-options="{{data2}}, {type:'bar', height:20, barWidth:5, barSpacing:1, barColor:'#dce5ec'}" 
              class="sparkline inline">loading...
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / main header -->
    <div class="wrapper-md" ng-controller="FlotChartDemoCtrl">
      <!-- stats -->
      <div class="row">
        <div class="col-md-5">
          <div class="row row-sm text-center">
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="h1 text-info font-thin h1">20</div>
                <span class="text-muted text-xs">Hospices</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-primary item">
                <span class="text-white font-thin h1 block">25</span>
                <span class="text-muted text-xs">New Orders</span>
                <span class="bottom text-right w-full">
                  <i class="fa fa-cloud-upload text-muted m-r-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <a href class="block panel padder-v bg-info item">
                <span class="text-white font-thin h1 block">13</span>
                <span class="text-muted text-xs">Payment Received</span>
                <span class="top text-left">
                  <i class="fa fa-caret-up text-warning m-l-sm"></i>
                </span>
              </a>
            </div>
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="font-thin h1">129</div>
                <span class="text-muted text-xs">New Equipments</span>
                <div class="bottom text-left">
                  <i class="fa fa-caret-up text-warning m-l-sm"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 m-b-md">
              <div class="r bg-light dker item hbox no-border">
                <div class="col w-xs v-middle hidden-md">
                  <div ng-init="data1=[60,40]" ui-jq="sparkline" ui-options="{{data1}}, {type:'pie', height:40, sliceColors:['{{app.color.warning}}','#fff']}" class="sparkline inline"></div>
                </div>
                <div class="col dk padder-v r-r">
                  <div class="text-primary-dk font-thin h1"><span>$12,670</span></div>
                  <span class="text-muted text-xs">Total Revenue, 60% of the goal</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel wrapper">
            <label class="i-switch bg-warning pull-right" ng-init="showSpline=true">
              <input type="checkbox" ng-model="showSpline">
              <i></i>
            </label>
            <h4 class="font-thin m-t-none m-b text-muted">Latest Company Sales</h4>
            <div ui-jq="plot" ui-refresh="showSpline" ui-options="
              [
                { data: {{d0_1}}, label:'Capped Equipments', points: { show: true, radius: 1}, splines: { show: showSpline, tension: 0.4, lineWidth: 1, fill: 0.8 } },
                { data: {{d0_2}}, label:'Non-capped Equipments', points: { show: true, radius: 1}, splines: { show: showSpline, tension: 0.4, lineWidth: 1, fill: 0.8 } }
              ], 
              {
                colors: ['{{app.color.info}}', '{{app.color.primary}}'],
                series: { shadowSize: 3 },
                xaxis:{ font: { color: '#a1a7ac' } },
                yaxis:{ font: { color: '#a1a7ac' }, max:20 },
                grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#dce5ec' },
                tooltip: true,
                tooltipOpts: { content: 'Sales of %x.1 is %y.4',  defaultTheme: false, shifts: { x: 10, y: -25 } }
              }
            " style="height:246px" >
            </div>
          </div>
        </div>
      </div>
      <!-- / stats -->

      <!-- service -->
      <div class="panel hbox hbox-auto-xs no-border">
        <div class="col wrapper">
          <i class="fa fa-circle-o text-info m-r-sm pull-right"></i>
          <h4 class="font-thin m-t-none m-b-none text-primary-lt">Managed Company Resources</h4>
          <span class="m-b block text-sm text-muted">Service report of this year (updated 1 hour ago)</span>
          <div ui-jq="plot" ui-options="
            [
              { data: {{d4}}, lines: { show: true, lineWidth: 1, fill:true, fillColor: { colors: [{opacity: 0.2}, {opacity: 0.8}] } } }
            ], 
            {
              colors: ['{{app.color.light}}'],
              series: { shadowSize: 3 },
              xaxis:{ show:false },
              yaxis:{ font: { color: '#a1a7ac' } },
              grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#dce5ec' },
              tooltip: true,
              tooltipOpts: { content: '%s of %x.1 is %y.4',  defaultTheme: false, shifts: { x: 10, y: -25 } }
            }
          " style="height:240px" >
          </div>
        </div>
        <div class="col wrapper-lg w-lg bg-light dk r-r">
          <h4 class="font-thin m-t-none m-b">Reports</h4>
          <div class="">
            <div class="">
              <span class="pull-right text-primary">60%</span>
              <span>New Equipments</span>
            </div>
            <progressbar value="60" class="progress-xs m-t-sm bg-white" animate="true" type="primary"></progressbar>
            <div class="">
              <span class="pull-right text-info">35%</span>
              <span>Disposable Equipments</span>
            </div>
            <progressbar value="35" class="progress-xs m-t-sm bg-white" animate="true" type="info"></progressbar>
            <div class="">
              <span class="pull-right text-warning">25%</span>
              <span>Capped-Equipments</span>
            </div>
            <progressbar value="25" class="progress-xs m-t-sm bg-white" animate="true" type="warning"></progressbar>
          </div>
          <!-- <p class="text-muted">Dales nisi nec adipiscing elit. Morbi id neque quam. Aliquam sollicitudin venenatis</p> -->
        </div>
      </div>
      <!-- / service -->


      <!-- tasks -->

      <!-- / tasks -->
    </div>
  </div>
  <!-- / main -->
  <!-- right col -->
  <div class="col w-md bg-white-only b-l bg-auto no-border-xs">
    <tabset class="nav-tabs-alt" justified="true">
     
      
      <tab>
        <tab-heading>
          <i class="glyphicon glyphicon-transfer text-md text-muted wrapper-sm"></i>
        </tab-heading>
        <div class="wrapper-md">
          <div class="m-b-sm text-md">Transactions</div>
          <ul class="list-group list-group-sm list-group-sp list-group-alt auto m-t">
            <li class="list-group-item">
              <span class="text-muted">Delivered to Hospice Del Sol at 3:00 pm</span>
              <span class="block text-md text-info">Patient Medical Record No.
P6319</span>
            </li>
            <li class="list-group-item">
              <span class="text-muted">Pickup from Compassion Care at 1:00 pm</span>
              <span class="block text-md text-primary">Patient Medical Record No.
P5629</span>
            </li>
            <li class="list-group-item">
              <span class="text-muted">Delivered Angel Eye Hospice at 9:00 am</span>
              <span class="block text-md text-warning">Patient Medical Record No.
P6812</span>
            </li>
            <li class="list-group-item">
              <span class="text-muted">Send Invoice to Angel Eye Hospice at 8:00 am</span>
              <span class="block text-md">Patient Medical Record No.
P3649</span>
            </li>
          </ul>
        </div>
      </tab>      
    </tabset>
    <div class="padder-md">      
      <!-- streamline -->
      <div class="m-b text-md">Recent Activity</div>
      <div class="streamline b-l m-b">
        <div class="sl-item">
          <div class="m-l">
            <div class="text-muted">5 minutes ago</div>
            <p><a href class="text-info">Robert</a> commented your post.</p>
          </div>
        </div>
        <div class="sl-item">
          <div class="m-l">
            <div class="text-muted">11:30</div>
            <p>Join conference with the team</p>
          </div>
        </div>
        <div class="sl-item b-success b-l">
          <div class="m-l">
            <div class="text-muted">10:30</div>
            <p>Call to customer <a href class="text-info">Patient Medical Record No.
P3649</a> and discuss the detail.</p>
          </div>
        </div>
       <!--  <div class="bg-info wrapper-sm m-l-n m-r-n m-b r r-2x">
          Create tasks for the team
        </div> -->
        <div class="sl-item b-primary b-l">
          <div class="m-l">
            <div class="text-muted">Wed, 25 Mar</div>
            <p>Finished the delivery to<a href class="text-info"> Angel Eye Hospice</a>.</p>
          </div>
        </div>
       
        <div class="sl-item b-info b-l">
          <div class="m-l">
            <div class="text-muted">Sat, 5 Mar</div>
            <p>Prepare for the presentation</p>
          </div>
        </div>
       
        <div class="sl-item b-l">
          <div class="m-l">
            <div class="text-muted">Thu, 17 Jan</div>
            <p>Follow up the invoice sent to Compassion Home Care </p>
          </div>
        </div>
      </div>
      <!-- / streamline -->
    </div>
  </div>
  <!-- / right col -->
</div>