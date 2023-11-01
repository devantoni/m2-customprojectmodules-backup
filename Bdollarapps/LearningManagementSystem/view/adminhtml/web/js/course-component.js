define([
    'jquery',
    'mage/template',
    'Magento_Ui/js/modal/alert',
    "Magento_Ui/js/modal/modal",
    "mage/apply/main",
    "prototype",
    "extjs/ext-tree-checkbox",
    'mage/adminhtml/wysiwyg/tiny_mce/setup'
], function ($, mageTemplate, alert, modal) {

    var config = {}, 
        editor;
    $.widget('mage.custom_product',{
        options: {
            pageGroupTemplate:'',
            productGroupTemplate:'',
            pageGroupContainer : '#page_group_container',
            sectionWrapper : "#section-wrapper",
            sectionContainter:"page_group_container",
            itemCount : 0,
        },
        _create: function(config) {
            var self = this;
            // if (self.options.savedData != '' ||self.options.savedData !==null ) {
            //     var as = JSON.parse(self.options.savedData);
            // }
            //let retreivedData = Object.entries(self.options.savedData);
            //if (retreivedData.length > 1) {
                var retreivedData = self.options.savedData;
                console.log("retreivedData",retreivedData);
                if (Object.keys(retreivedData).length > 0 ){
                    var i = 1;
                    $.each(retreivedData, function( index, value ) {
                        
                        self.loadSavedData(value['courses'],value['title'],i,value['section_id']);
                        
                        i++;
                    });
                    self.options.itemCount = Object.keys(retreivedData).length;
                }
                //
            //}
           
            // this.options.pageGroupTemplate = '<div>Binded data <%- data.packageItemCount %> </div>';
            // pageGroupTemplateObj = mageTemplate(this.options.pageGroupTemplate);
            // var pageGroupRender = pageGroupTemplateObj({
            //     data: this.options
            // })
            // $(this.options.pageGroupContainer).append(pageGroupRender);
            $(document).on('click', '.course-items-catalog-add', function() {
                self.addPageGroup();
                
            });
            $(document).on('click', '.course-items-action-delete', function(e) {
                let removeSectionId = $(this).attr('data-sectiondbid');
                self.removePageGroup($(this).attr('item-delete'));
                if (removeSectionId!= "") {
                    var sectionValue = $('#remove_section_input').val();
                    if (sectionValue!= ''){
                        var finalSectionRemove = sectionValue+","+removeSectionId;
                    } else {
                        var finalSectionRemove = removeSectionId;
                    } 
                }
                $('#remove_section_input').val(finalSectionRemove);
                //self.decreasePackageItemCount();
            });
            $(document).on('click', '#course-additon', function(e) {
                
                self.addCourseGroup($(this).attr('data-sectionid'));
                //self.decreasePackageItemCount();
            });
            $(document).on('click', '#remove_course', function(e) {
                let courseId = $(this).attr('data-actionremove');
                let sectionId = $(this).attr('data-sectionRemoveId');
                let removecontentId = $(this).attr('data-contentdbid');
                self.removeCourseRow(sectionId,courseId);
                if (removecontentId!= "") {
                    var courseValue = $('#remove_course_input').val();
                    if (courseValue!= ''){
                        var finalCourseRemove = courseValue+","+removecontentId;
                    } else {
                        var finalCourseRemove = removecontentId;
                    } 
                }
                $('#remove_course_input').val(finalCourseRemove);
                
                //self.decreasePackageItemCount();
            });
            $(document).on('change', '.course-type-select', function(e) {
                
              
                var idFields = $(this).attr('data-filemap');
                var id = $(this).attr('id');
                $('#'+idFields+'').val("");
                $('#path_'+idFields+'').val("");
                $('#url_'+idFields+'').val("");
                $('#file_label_'+idFields+'').html("");
                //self.decreasePackageItemCount();
            });
            $(document).on('change', '.file-upload', function(e) {
               
                var fileName = e.target.files[0].name;
                var idForm = e.target.id;
                var idInputs = $(this).attr('data-uniqueid');
                var dataTypeId = $(this).attr('data-typeid');
                var formData = new FormData();
                var file_obj = document.getElementById(idForm);
                var file_name_extension = file_obj.files[0].name;
                //console.log("files[0]",file_obj.files[0]);
                formData.append(''+idForm+'', file_obj.files[0]);
                formData.append('id', idForm);
                formData.append('form_key', window.FORM_KEY);
                var extension = file_name_extension.split('.').pop(); 
                console.log("extension",extension);
                var typeId = $('#'+dataTypeId+'').val();
                formData.append('typeId',typeId);
                if (typeId == '1') {

                    if (extension == 'mp4' || extension == 'ogg') {

                        $('body').trigger('processStart');
                        $.ajax({
                            url: self.options.uploadUrl,
                            cache: false,
                            type: "POST",
                            enctype: 'multipart/form-data',
                            contentType: false,
                            processData: false,
                            data: formData,
                            dataType: "json",
                            success: function(response){
                                $('#'+idInputs+'').val(response.name);
                                $('#path_'+idInputs+'').val(response.file);
                                $('#url_'+idInputs+'').val(response.url);
                                $('#file_label_'+idInputs+'').html(""+response.name+"");
                                $('body').trigger('processStop');
                            }
                        });
                    }else {

                        alert({
                            title: $.mage.__('Attention'),
                            content: $.mage.__('File must be in mp4 or ogg format.'),
                            actions: {
                                always: function(){
                                    $('#'+idInputs+'').val("");
                                    $('#path_'+idInputs+'').val("");
                                    $('#url_'+idInputs+'').val("");
                                    $('#file_label_'+idInputs+'').html("");
                                }
                            }
                        });

                        
                    }

                } else {
                    if (extension == 'pdf' || extension == 'txt' || extension == 'zip') {

                        $('body').trigger('processStart');
                        $.ajax({
                            url: self.options.uploadUrl,
                            cache: false,
                            type: "POST",
                            enctype: 'multipart/form-data',
                            contentType: false,
                            processData: false,
                            data: formData,
                            dataType: "json",
                            success: function(response){
                                $('#'+idInputs+'').val(response.name);
                                $('#path_'+idInputs+'').val(response.file);
                                $('#url_'+idInputs+'').val(response.url);
                                $('#file_label_'+idInputs+'').html(""+response.name+"");
                                $('body').trigger('processStop');
                            }
                        });
                    }else {

                        alert({
                            title: $.mage.__('Attention'),
                            content: $.mage.__('File must be in pdf,txt,zip format.'),
                            actions: {
                                always: function(){
                                    $('#'+idInputs+'').val("");
                                    $('#path_'+idInputs+'').val("");
                                    $('#url_'+idInputs+'').val("");
                                    $('#file_label_'+idInputs+'').html("");
                                }
                            }
                        });

                        
                    }
                    
                }
                




                //self.decreasePackageItemCount();
            });            
        },
        addPageGroup: function() {
            {/* this.options.pageGroupTemplate ='<fieldset id="<%- data.sectionContainter%>_<%- data.itemCount %>">' +
                        '<div class="fieldset-wrapper page_group_container opened">' +
                            '<div class="fieldset-wrapper-title">' + 
                                 '<label for="widget_instance[<%- data.itemCount %>][page_group]">Section Title <span class="required">*</span></label>' +
                                    '<input class="admin__control-text" type="text" name="product[course_section][<%- data.itemCount %>][title]"  maxlength="255">' +
                                       '<div class="actions">' +
                                         '<button title="Remove Layout Update" type="button" class="action-default scalable course-items-action-delete" item-delete = "<%- data.itemCount %>"></button>' +
                                       '</div>' + 
                             '</div>' + 
                        '</div>' +
                        '</fieldset>'; */}
            this.options.pageGroupTemplate ='<tr class="data-row" data-secid = "<%- data.itemCount %>" id="<%- data.sectionContainter%>_<%- data.itemCount %>">' +
                   '<td class="admin__collapsible-block-wrapper _no-header _show">' + 
                      '<div class="fieldset-wrapper admin__collapsible-block-wrapper _show">' + 
                        '<div class="fieldset-wrapper-title">' + 
                            '<div class="admin__collapsible-title"><span>Section Title</span> <span class="required-field">* </span>' +
                              '<input required data-form-part="product_form" class="admin__control-text requried" type="text" name="course_section[<%- data.itemCount %>][section_title]"  maxlength="25"/>' +
                              '<input data-form-part="product_form" class="admin__control-text requried" type="hidden" name="course_section[<%- data.itemCount %>][section_id]" value=""/>' +
                            '</div>' +
                              '<button class="action-delete course-items-action-delete" data-sectiondbid="" type="button" title="Delete" item-delete = "<%- data.itemCount %>">' +
                                '<span>Delete</span>' +
                              '</button>'+
                        '</div>' +
                        '<div class="admin__field-control" data-role="grid-wrapper" id="grid_wrapper_<%- data.itemCount %>" data-countcourse="1">'+
                          '<div class="admin__control-table-wrapper">' +
                            ' <table class="admin__dynamic-rows admin__control-table">' +
                                '<thead>' +
                                  '<tr>' +
                                    '<th></th>' +
                                    '<th  class="_no-header"> <span>Content Title</span> <span class="required-field">*</span></th>'+
                                    '<th class="_no-header" ><span>Description</span> <span class="required-field">*</span></th>'+
                                    '<th class="_no-header"><span>Type</span> <span class="required-field">*</span></th>' +
                                    '<th class="_no-header" ><span>Preview</span> <span class="required-field">*</span></th>' +
                                    '<th class="_no-header"> <span>Attachement</span> <span class="required-field">*</span></th>' +
                                    '<th class="_no-header"><span>Delete</span></th>'+
                                  '</tr>' +
                                '</thead>' +
                                '<tbody id="tbody_container_<%- data.itemCount %>">' +
                                    '<tr class="data-row" id="tr_row_1" data-repeat-index="1">'+
                                        '<td><input data-form-part="product_form" type="hidden" name="course_section[<%- data.itemCount %>][course_data][1][course_entityid]" value=""/></td>' +
                                        '<td>'+
                                        '<div class="admin__field _no-header">' +
                                        '<div class="admin__field-control">' +
                                            ' <input data-form-part="product_form" class="admin__control-text" type="text"  name="course_section[<%- data.itemCount %>][course_data][1][course_title]" aria-describedby="notice-NJCJMP2" id="NJCJMP2" maxlength="255"/>' +
                                            '</div>' +
                                        '</div>' + 
                                        '</td>'+
                                        '<td>'+
                                        '<div class="admin__field _no-header">' +
                                        '<div class="admin__field-control">' +
                                            ' <textarea data-form-part="product_form" id="course_description_<%- data.itemCount %>" class="admin__control-textarea" name="course_section[<%- data.itemCount %>][course_data][1][course_description]" aria-describedby="notice-NJCJMP2" id="NJCJMP2" cols="15" rows="2"/>' +
                                            '</textarea>' +
                                            '</div>' +
                                        '</div>' + 
                                        '</td>'+
                                        '<td>'+
                                        '<div class="admin__field _no-header">' +
                                        '<div class="admin__field-control">' +
                                            ' <select data-filemap="file_upload_sec_<%- data.itemCount %>_cour_1" data-form-part="product_form" class="admin__control-select course-type-select" name="course_section[<%- data.itemCount %>][course_data][1][course_type]" aria-describedby="notice-NJCJMP2" id="course_typeid_<%- data.itemCount %>_1">' +
                                            '<option data-title="Content" value="1">Content</option>' +
                                            '<option data-title="Assignment" value="2">Assignment</option>' +
                                            '</select>' +
                                            '</div>' +
                                        '</div>' + 
                                        '</td>'+
                                        '<td>'+
                                        '<div class="admin__field _no-header">' +
                                        '<div class="admin__field-control">' +
                                            ' <select data-form-part="product_form" class="admin__control-select" name="course_section[<%- data.itemCount %>][course_data][1][course_preview]" aria-describedby="notice-NJCJMP2" id="NJCJMP2">' +
                                            '<option data-title="Yes" value="1">Yes</option>' +
                                            '<option data-title="No" value="0">No</option>' +
                                            '</select>' +
                                            '</div>' +
                                        '</div>' + 
                                        '</td>'+
                                        '<td>'+
                                        '<div class="admin__field _no-header">' +
                                        '<div class="admin__field-control">' +
                                            '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="file_upload_sec_<%- data.itemCount %>_cour_1"  name="course_section[<%- data.itemCount %>][course_data][1][course_filename]" aria-describedby="notice-NJCJMP2" />' +
                                            '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="path_file_upload_sec_<%- data.itemCount %>_cour_1"  name="course_section[<%- data.itemCount %>][course_data][1][course_filepath]" aria-describedby="notice-NJCJMP2" />' +
                                            '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="url_file_upload_sec_<%- data.itemCount %>_cour_1"  name="course_section[<%- data.itemCount %>][course_data][1][course_fileurl]" aria-describedby="notice-NJCJMP2" />' +
                                            '<div class="file-uploader admin__field" data-role="drop-zone">' +
                                            '<input class="file-upload" type="file" name="course_section[<%- data.itemCount %>][course_data][1][course_file_uploaded]" id="file_upload_<%- data.itemCount %>_1" data-typeid="course_typeid_<%- data.itemCount %>_1" data-uniqueid ="file_upload_sec_<%- data.itemCount %>_cour_1" style="display:none;">'+
                                            '<label for ="file_upload_<%- data.itemCount %>_1" class="file-uploader-button action-default">Upload</label>'+
                                            '<label id="file_label_file_upload_sec_<%- data.itemCount %>_cour_1"></label>'+
                                            '</div>'+
                                            '</div>' +
                                        '</div>' + 
                                        '</td>'+
                                        '<td>'+
                                        '<button class="action-delete" data-contentdbid="" id="remove_course" data-sectionRemoveId="<%- data.itemCount %>" data-actionremove="1">' +
                                        '<span></span>'+
                                        '</button>' + 
                                        '</td>'+
                                    '</tr>'+
                                '</tbody>' +  
                                '</table>' +
                                '<div>'+
                                '<button class="action-secondary" id="course-additon" data-sectionid = "<%- data.itemCount %>">Add Course</button>' +
                                '</div>'+
                      '</div>' +
                   '</td></tr>';
                      
            
            pageGroupTemplateObj = mageTemplate(this.options.pageGroupTemplate);
            this.increasePackageItemCount();
            
            var pageGroupRender = pageGroupTemplateObj({
                data: this.options
            })
            $(this.options.sectionWrapper).append(pageGroupRender);
            
            
        },
        removePageGroup : function(index) {

            $("#page_group_container_"+index+"").remove();
        },
        increasePackageItemCount: function () {
            this.options.itemCount = this.options.itemCount + 1;
        },
        decreasePackageItemCount: function () {
            this.options.itemCount = this.options.itemCount - 1;
        },
        addCourseGroup : function(sectionId) {
           
            let trIndex = this.updateIncreasedCourseCount(sectionId);
            $('#grid_wrapper_'+sectionId+'').attr('data-countcourse',trIndex);
            let sectionIdItem = $('#page_group_container_'+sectionId+'').attr('data-secid');
       
            this.options.pageGroupTemplate = '<tr class="data-row" id="tr_row_<%- trIndex %>" data-repeat-index="<%- trIndex%>">'+
                '<td><input data-form-part="product_form" type="hidden" name="course_section[<%- sectionIdItem  %>][course_data][<%- trIndex %>][course_entityid]" value=""/></td>' +
                '<td>'+
                '<div class="admin__field _no-header">' +
                '<div class="admin__field-control">' +
                    ' <input data-form-part="product_form" class="admin__control-text" type="text"  name="course_section[<%- sectionIdItem  %>][course_data][<%- trIndex %>][course_title]" aria-describedby="notice-NJCJMP2" id="NJCJMP2" maxlength="255">' +
                    '</div>' +
                '</div>' + 
                '</td>'+
                '<td>'+
                '<div class="admin__field _no-header">' +
                '<div class="admin__field-control">' +
                    ' <textarea id="description_<%- trIndex %>" data-form-part="product_form" class="admin__control-textarea" name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_description]" aria-describedby="notice-NJCJMP2" id="NJCJMP2" cols="15" rows="2">' +
                    '</textarea>' +
                    '</div>' +
                '</div>' + 
                '</td>'+
                '<td>'+
                '<div class="admin__field _no-header">' +
                '<div class="admin__field-control">' +
                    ' <select data-filemap="file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>" data-form-part="product_form" class="admin__control-select course-type-select" name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_type]" aria-describedby="notice-NJCJMP2" id="course_typeid_<%- sectionIdItem %>_<%- trIndex %>">' +
                    '<option data-title="Content" value="1">Content</option>' +
                    '<option data-title="Assignment" value="2">Assignment</option>' +
                    '</select>'+
                    '</div>' +
                '</div>' + 
                '</td>'+
                '<td>'+
                '<div class="admin__field _no-header">' +
                '<div class="admin__field-control">' +
                    ' <select data-form-part="product_form" class="admin__control-select" name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_preview]" aria-describedby="notice-NJCJMP2" id="NJCJMP2">' +
                    '<option data-title="Yes" value="1">Yes</option>' +
                    '<option data-title="No" value="0">No</option>' +
                    '</select>'+
                    '</div>' +
                '</div>' + 
                '</td>'+
                '<td>'+
                    '<div class="admin__field _no-header">' +
                    '<div class="admin__field-control">' +
                        '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>"  name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_filename]" aria-describedby="notice-NJCJMP2" />' +
                        '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="path_file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>"  name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_filepath]" aria-describedby="notice-NJCJMP2" />' +
                        '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="url_file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>"  name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_fileurl]" aria-describedby="notice-NJCJMP2" />' +
                        '<div class="file-uploader admin__field" data-role="drop-zone">' +
                        '<input class="file-upload" type="file" name="course_section[<%- sectionIdItem %>][course_data][<%- trIndex %>][course_file_uploaded]" id="file_upload_<%- sectionIdItem %>_<%- trIndex %>" data-typeid="course_typeid_<%- sectionIdItem %>_<%- trIndex %>" data-uniqueid ="file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>" style="display:none;">'+
                        '<label for ="file_upload_<%- sectionIdItem %>_<%- trIndex %>" class="file-uploader-button action-default">Upload</label>'+
                        '<label id="file_label_file_upload_sec_<%- sectionIdItem %>_cour_<%- trIndex %>"></label>'+
                        '</div>'+
                        '</div>' +
                    '</div>' + 
                '</td>'+
                '<td>'+
                '<button class="action-delete" data-contentdbid="" id="remove_course" data-sectionRemoveId="<%- sectionIdItem %>" data-actionremove="<%- trIndex %>">' +
                '<span></span>'+
                '</button>' + 
                '</td>'+
            '</tr>';
            pageGroupTemplateObj = mageTemplate(this.options.pageGroupTemplate);
            var pageGroupRender = pageGroupTemplateObj({
                data: this.options,
                trIndex:trIndex,
                sectionIdItem:sectionIdItem
            })
            
            $('#tbody_container_'+sectionId+'').append(pageGroupRender);
            var wysiwygcompany_description = new wysiwygSetup("description_"+trIndex, {
                "width":"99%",  // defined width of editor
                "height":"200px", // height of editor
                "plugins":[{"name":"image"}], // for image
                "tinymce4":{"toolbar":"formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table charmap","plugins":"advlist autolink lists link charmap media noneditable table contextmenu paste code help table",
                }
            });
            wysiwygcompany_description.setup("exact");
            
        },
        updateIncreasedCourseCount: function(sectionId){
            let incCount = $('#grid_wrapper_'+sectionId+'').attr('data-countcourse');
            incCount = parseInt(incCount) + 1;
            return incCount;
          
        },
        updateDecreasedCourseCount: function(sectionId){
            let decCount = $('#grid_wrapper_'+sectionId+'').attr('data-countcourse');
            decCount = parseInt(decCount) - 1;
            return decCount;
            //$('#grid_wrapper_'+sectionId+'').attr('data-countcourse',decCount);
          
        },
        removeCourseRow : function(sectionId,courseId){
           
           $('#grid_wrapper_'+sectionId+'').find("#tr_row_"+courseId+"").remove();
           let decreaseUpdate = this.updateDecreasedCourseCount(sectionId);
           $('#grid_wrapper_'+sectionId+'').attr('data-countcourse',decreaseUpdate);
          
        },
        loadSavedData: function(retreivedCourseData,sectionTitle,sectionIndex,sectionId){
            
            this.options.pageGroupTemplate = '<tr class="data-row" data-secid = "<%- sectionIndex %>" id="<%- data.sectionContainter%>_<%- sectionIndex %>">' +
                        '<td class="admin__collapsible-block-wrapper _no-header _show">' + 
                        '<div class="fieldset-wrapper admin__collapsible-block-wrapper _show">' +
                        '<div class="fieldset-wrapper-title">' + 
                        '<div class="admin__collapsible-title"><span>Section Title</span> <span class="required-field">*</span>' +
                            '<input data-form-part="product_form" class="admin__control-text requried" type="text" name="course_section[<%- sectionIndex%>][section_title]" value="<%- sectionTitle%>"  maxlength="25"/>' +
                            '<input data-form-part="product_form" class="admin__control-text requried" type="hidden" name="course_section[<%- sectionIndex%>][section_id]" value="<%- sectionId%>"/>' +
                        '</div>' +
                        '<button class="action-delete course-items-action-delete" data-sectiondbid="<%- sectionId%>" type="button" title="Delete" item-delete = "<%- sectionIndex %>">' +
                        '<span>Delete</span>' +
                        '</button>'+ 
                        '</div>' + 
                        '<div class="admin__field-control" data-role="grid-wrapper" id="grid_wrapper_<%- sectionIndex%>" data-countcourse="<%- retreivedCourseData.length%>">'+
                        '<div class="admin__control-table-wrapper">' +
                        '<table class="admin__dynamic-rows admin__control-table">' +
                        '<thead>' +
                            '<tr>' +
                            '<th></th>' +
                            '<th  class="_no-header"> <span>Content Title <span class="required-field">*</span></span></th>'+
                            '<th class="_no-header" ><span>Description</span> <span class="required-field">*</span></th>'+
                            '<th class="_no-header"><span>Type</span> <span class="required-field">*</span></th>' +
                            '<th class="_no-header" ><span>Preview</span> <span class="required-field">*</span></th>' +
                            '<th class="_no-header"> <span>Attachement</span> <span class="required-field">*</span></th>' +
                            '<th class="_no-header"><span>Delete</span></th>'+
                            '</tr>' +
                        '</thead>' +
                        '<tbody id="tbody_container_<%- sectionIndex %>">' +
                        '<% for(var i=0; i < retreivedCourseData.length;i++) { var k = i + 1;%>'+
                        '<tr class="data-row" id="tr_row_<%- k %>" data-repeat-index="<%- k %>">'+
                            '<td><input data-form-part="product_form" type="hidden" name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_entityid]" value="<%- retreivedCourseData[i]["course_entityid"]%>"/></td>' +
                            '<td>'+
                            '<div class="admin__field _no-header">' +
                                '<div class="admin__field-control">' +
                                    ' <input data-form-part="product_form" class="admin__control-text" type="text"  name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_title]" aria-describedby="notice-NJCJMP2" value="<%- retreivedCourseData[i]["course_title"]%>" id="NJCJMP2" maxlength="255"/>' +
                                '</div>' +
                            '</div>' + 
                            '</td>'+
                            '<td>'+
                                '<div class="admin__field _no-header">' +
                                '<div class="admin__field-control">' +
                                    ' <textarea id="description_1" value="<%- retreivedCourseData[i]["course_description"]%>" data-form-part="product_form" class="admin__control-textarea" name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_description]" aria-describedby="notice-NJCJMP2" id="NJCJMP2" cols="15" rows="2">' +
                                    '<%- retreivedCourseData[i]["course_description"]%>'+
                                    '</textarea>' +
                                    '</div>' +
                                '</div>' + 
                            '</td>'+
                            '<td>'+
                                '<div class="admin__field _no-header">' +
                                '<div class="admin__field-control">' +
                                    ' <select data-filemap="file_upload_sec_<%- sectionIndex %>_cour_<%- k %>" data-form-part="product_form" class="admin__control-select course-type-select" name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_type]" aria-describedby="notice-NJCJMP2" id="course_typeid_<%- sectionIndex %>_<%- k %>">' +
                                    '<option data-title="Content" value="1" <% if(retreivedCourseData[i]["course_type"] === "1"){%> selected <% }%>>Content</option>' +
                                    '<option data-title="Assignment" value="2" <% if(retreivedCourseData[i]["course_type"] ==="2"){%> selected <%}%>>Assignment</option>' +
                                    '</select>' +
                                    '</div>' +
                                '</div>' + 
                            '</td>'+
                            '<td>'+
                                '<div class="admin__field _no-header">' +
                                '<div class="admin__field-control">' +
                                    ' <select data-form-part="product_form" class="admin__control-select" name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_preview]" aria-describedby="notice-NJCJMP2" id="NJCJMP2">' +
                                    '<option data-title="Yes" value="1" <% if(retreivedCourseData[i]["course_preview"] === "1"){%> selected <% }%>>Yes</option>' +
                                    '<option data-title="No" value="0" <% if(retreivedCourseData[i]["course_preview"] ==="0"){%> selected <%}%>>No</option>' +
                                    '</select>' +
                                    '</div>' +
                                '</div>' + 
                            '</td>'+
                            '<td>'+
                                '<div class="admin__field _no-header">' +
                                '<div class="admin__field-control">' +
                                    '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="file_upload_sec_<%- sectionIndex %>_cour_<%- k %>"  name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_filename]" value="<%- retreivedCourseData[i]["course_filename"]%>" aria-describedby="notice-NJCJMP2" />' +
                                    '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="path_file_upload_sec_<%- sectionIndex %>_cour_<%- k %>"  name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_filepath]" value="<%- retreivedCourseData[i]["course_filepath"]%>" aria-describedby="notice-NJCJMP2" />' +
                                    '<input data-form-part="product_form" class="admin__control-text" type="hidden" id="url_file_upload_sec_<%- sectionIndex %>_cour_<%- k %>"  name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_fileurl]" value="<%- retreivedCourseData[i]["course_fileurl"]%>" aria-describedby="notice-NJCJMP2" />' +
                                    '<div class="file-uploader admin__field" data-role="drop-zone">' +
                                    '<input class="file-upload" type="file" name="course_section[<%- sectionIndex %>][course_data][<%- k %>][course_file_uploaded]" id="file_upload_<%- sectionIndex %>_<%- k %>" data-typeid="course_typeid_<%- sectionIndex %>_<%- k %>" data-uniqueid ="file_upload_sec_<%- sectionIndex %>_cour_<%- k %>" style="display:none;">'+
                                    '<label for ="file_upload_<%- sectionIndex %>_<%- k %>" class="file-uploader-button action-default">Upload</label>'+
                                    '<label id="file_label_file_upload_sec_<%- sectionIndex %>_cour_<%- k %>"><%- retreivedCourseData[i]["course_filename"]%></label>'+
                                    '</div>'+
                                    '</div>' +
                                '</div>' + 
                            '</td>'+
                            '<td>'+
                                '<button class="action-delete"  data-contentdbid="<%- retreivedCourseData[i]["course_entityid"]%>" id="remove_course" data-sectionRemoveId="<%- sectionIndex %>" data-actionremove="<%- k %>">' +
                                '<span></span>'+
                                '</button>' + 
                            '</td>'+
                        '</tr>'+
                        '<% } %>'+
                        '</tbody>' + 
                        '</table>' +
                        '</div>' +
                        '<div>'+
                           '<button class="action-secondary" id="course-additon" data-sectionid = "<%- sectionIndex %>">Add Course</button>' +
                        '</div>' +
                        '</div>' +
                        '</td></tr>';
            pageGroupTemplateObj = mageTemplate(this.options.pageGroupTemplate);
            var pageGroupRender = pageGroupTemplateObj({
                data: this.options,
                sectionTitle:sectionTitle,
                sectionIndex:sectionIndex,
                sectionId:sectionId,
                retreivedCourseData:retreivedCourseData
            });
            $(this.options.sectionWrapper).append(pageGroupRender);
            var wysiwygcompany_description = new wysiwygSetup("description_"+sectionIndex, {
                "width":"99%",  // defined width of editor
                "height":"200px", // height of editor
                "plugins":[{"name":"image"}], // for image
                "tinymce4":{"toolbar":"formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table charmap","plugins":"advlist autolink lists link charmap media noneditable table contextmenu paste code help table",
                }
            });
            wysiwygcompany_description.setup("exact");
        }
       
        
    });
    return $.mage.custom_product;
}); 