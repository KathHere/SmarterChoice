/**********************************************************************************
 *  copyright 2015 - JR
 ***********************************************************************************

 DOCUMENTATION
        modalbox(url,options);

        options:

                header: "", //title of the modal <required>
                id:"globalModal", //id of the modal <optional>
                class:"modalclass", //class of the modal <optional>
                noclose: false, //default is false
                content: false, //default is false, this is application if URL is empty
                footer:true,    //default is true , version2
                button:true,    //default is true , version1 of button
                afterLoad:function(data){}, //callback after loading the content, applicable only on URL request
                buttons: [{
                        text: "OK",
                        type: "success",
                        click: function() { //click event
                        }
                    }, {
                        text: "Close",
                        type: "danger",
                        click: function() {
                            closeModalbox();
                        }
                    }

        Example:

         USAGE 1
            //if content from URL
            modalbox('testing.html',{
                    header:"<span class='fa fa-plus'></span> test",
                    id:"helloworld",
                    class:"modal-fullscreen",
                    buttons: [{
                          text: "Close",
                          type: "danger",
                          click: function() {
                            alert('testing');
                              closeModalbox('helloworld');
                          }
                      }],
                    afterLoad:function(response){
                        console.log(response);
                    }
            });

         USAGE 2
            //if on call content
            modalbox(false,{
                    header:"<span class='fa fa-plus'></span> test",
                    id:"helloworld",
                    content:"<h4>Hello world</h4>",
                    class:"modal-fullscreen",
                    buttons: [{
                          text: "Close",
                          type: "danger",
                          click: function() {
                            alert('testing');
                              closeModalbox('helloworld');
                          }
                      }],
                    afterLoad:function(response){
                        console.log(response);
                    }
            });


*/
var $msgModal = {};
function modalbox(url, options)
{
    var defaults = {
        header: "Response Required",
        id:"globalModal",
        class:"modalclass",
        noclose: false,
        content: false,
        footer:true,
        button:true,
        backdrop:true,
        width:"90%",
        afterLoad:function(data){},
        buttons: [{
                text: "OK",
                type: "success",
                click: function() {
                    alert(1);
                }
            }, {
                text: "Close",
                type: "danger",
                click: function() {
                    closeModalbox();
                }
            }]

    }
    var opts    = $.extend({}, defaults, options);
    if(opts.id=="" || opts.id=="globalModal" )
    {
         var lengthmodal = $('body').find('div[id*="globalModal"]').length;
         opts.id = "globalModal"+lengthmodal;
    }
    var width = "70%";
    if(typeof opts.width != "undefined" && opts.width!="")
    {
        width = "style='width:"+opts.width+"'";
    }
    var content_class = "";
    if(typeof opts.content_class !="undefined")
    {
        content_class = opts.content_class;
    }
    var modalHTML =  '<!-- Modal -->'+
                    '<div  class="modal fade '+opts.class+'" id="'+opts.id+'">'+
                      '<div class="modal-dialog" '+width+'>'+
                        '<div class="modal-content '+content_class+'">'+
                            '<div class="modal-custom-wrapper">'+
                                  '<div class="modal-header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                                  //  '<h4 class="modal-title" id="myModalLabel"></h4>'+
                                  '</div>'+
                                  '<div class="modal-body clearfix"></div>'+
                                  '<div class="modal-footer"></div>'+
                            '</div>'+
                        '</div><!-- /.modal-content -->'+
                      '</div><!-- /.modal-dialog -->'+
                    '</div><!-- /.modal --> ' ;

    //append to body
    if($('body').find('#'+opts.id).length<1)
    {
        $('body').append(modalHTML).promise().done(function(){
            $msgModal  = $('body #'+opts.id);
            $msgModal.modal({
                      backdrop:opts.backdrop,
                      show: false,
                      keyboard: false
                    });
            if(opts.header)
            {
                //set header
                $msgModal.find('.modal-header').append(opts.header);
            }
            else
            {
                $msgModal.find('.modal-header').hide();
            }
            

            //close button on the title bar
            if (opts.noclose) {
                $msgModal.find('button.close').css("display", "none");
            } else {
                $msgModal.find('button.close').css("display", "inline");
            }

            //buttons
            var btns = opts.buttons;
            var footer = $msgModal.find(".modal-footer");
            //initiate
            footer.empty();
            if (btns.length == 0 || opts.footer==false || opts.button==false) {
                footer.hide();
            }
            else {
                footer.show();
            }
            for (var i = 0; i < btns.length; i++) {
                btns[i].type = btns[i].type || 'default';
                btn = $('<button type="button" class="btn btn-' + btns[i].type + '">' + btns[i].text + '</button>');
                btn.click(btns[i].click);
                footer.append(btn);
            }
            
            $msgModal.find('.modal-body').html("<h3 style='text-align:center;'><span class='fa fa-spin fa-spinner'></span></h3>");
            $msgModal.modal('show');

            if (url == "" && opts.content === false) {
                return;
            }

            if (url === false && opts.content !== false) {
                $msgModal
                        .find('.modal-body').html(opts.content)
                        .modal('show');
            } else {
                var reqid = "reqid="+new Date().getTime();
                var url_data = (url.indexOf("?") > -1)? url+"&"+reqid : url+"?"+reqid; 
                $.get(url_data, function(data) {
                    setTimeout(function(){
                        $msgModal.find('.modal-body').html(data);
                       
                        if(typeof(opts.afterLoad) == 'function')
                        {
                            opts.afterLoad.call(undefined,data);
                        }
                    },10);

                }, "text");
            }

            $msgModal.on('hide.bs.modal', function () {
                 $(this).data('bs.modal', null);
                $(this).remove();
            });
        });
    }
}

function closeModalbox(param) {
    var id = "";
    if(typeof param === 'undefined')
    {
        id = $('body').find("#globalModal");
    }
    else
    {
        id = $('body').find("#"+param);
    }
    id.modal('hide');
    id.on('hide.bs.modal', function () {
         $(this).data('bs.modal', null);
    });
}