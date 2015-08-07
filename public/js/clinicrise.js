
$(function(){

    // fade out messages in all the best
    window.setTimeout(function() { $(".alert-danger").alert('close'); }, 5000);
    window.setTimeout(function() { $(".alert-info").alert('close'); }, 5000);
    window.setTimeout(function() { $(".alert-warning").alert('close'); }, 5000);
    window.setTimeout(function() { $(".alert-success").alert('close'); }, 5000);

    var tenantTable = $('#tenantTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": [1,2,3,4,5,6] },
            { "orderable": false, "targets": [1,2,3,4,5,6] }
        ]
    });

    var diagnosisTable = $('#diagnosisTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": 1 },
            { "orderable": false, "targets": 1 }
        ],
        "iDisplayLength": 25
    });

    var insuranceTable = $('#insuranceTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": 1 },
            { "orderable": false, "targets": 1 }
        ],
        "iDisplayLength": 25
    });

    var reasonTable = $('#reasonTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": 1 },
            { "orderable": false, "targets": 1 }
        ],
        "iDisplayLength": 25
    });

    var locationTable = $('#locationTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": 1 },
            { "orderable": false, "targets": 1 }
        ],
        "iDisplayLength": 25
    });

    var userTable = $('#userTable').DataTable({
        "order": [[ 0, "asc" ]],
        "stateSave": true,
        "columnDefs": [
            { "searchable": false, "targets": 2 },
            { "orderable": false, "targets": 2 }
        ],
        "iDisplayLength": 25
    });

    $('#save-diagnosis').on('click', function() {
        var jsonData = [];
        $('#diagnosisTable tr td input').each(function(index) {
            var id = $(this).prop('id');
            var value = $(this).val();
            var data = {};
            if (id && value)
            {

                data['id'] = id;
                data['value'] = value;
                jsonData.push(data);
            } else {
                if (value !== '')
                {
                    data['id'] = '';
                    data['value'] = value;
                    jsonData.push(data);
                }
            }

        });
        $.ajax({
            type: "POST",
            url:    "/admin/diagnosis/save",
            data: { 'data' : jsonData, 'csrf_token': $('.csrf_token').val(), 'practice_id': $('#practice_id').val()},
            success: function(data)
            {
                if (data == 'successsavingdiagnosis')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Diagnosis Successfully Saved!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/diagnosis/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });

    $('#save-insurance').on('click', function() {
        var jsonData = [];
        $('#insuranceTable tr td input').each(function(index) {
            var id = $(this).prop('id');
            var value = $(this).val();
            var data = {};
            if (id && value)
            {
                data['id'] = id;
                data['value'] = value;
            } else {
                if (value !== '')
                {
                    data['id'] = '';
                    data['value'] = value;
                }
            }
            jsonData.push(data);
        });
        $.ajax({
            type: "POST",
            url: "/admin/insurance/save",
            data: {'data':jsonData, 'csrf_token': $('.csrf_token').val(), 'practice_id': $('#practice_id').val()},
            success: function(data)
            {
                if (data == 'successsavinginsurance')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Insurance Successfully Saved!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/insurance/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });

    $('#save-reason').on('click', function() {
        var jsonData = [];
        $('#reasonTable tr td input').each(function(index) {
            var id = $(this).prop('id');
            var value = $(this).val();
            var data = {};
            if (id && value)
            {
                data['id'] = id;
                data['value'] = value;
            } else {
                if (value !== '')
                {
                    data['id'] = '';
                    data['value'] = value;
                }
            }
            jsonData.push(data);
        });
        $.ajax({
            type: "POST",
            url: "/admin/reason/save",
            data: {'data':jsonData, 'csrf_token': $('.csrf_token').val(), 'practice_id': $('#practice_id').val()},
            success: function(data)
            {
                if (data == 'successsavingreason')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Insurance Successfully Saved!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/reason/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });


    $('#save-location').on('click', function() {
        var jsonData = [];
        $('#locationTable tr td input').each(function(index) {
            var id = $(this).prop('id');
            var value = $(this).val();
            var data = {};
            if (id && value)
            {
                data['id'] = id;
                data['value'] = value;
            } else {
                if (value !== '')
                {
                    data['id'] = '';
                    data['value'] = value;
                }
            }
            jsonData.push(data);
        });
        $.ajax({
            type: "POST",
            url: "/admin/location/save",
            data: {'data':jsonData, 'csrf_token': $('.csrf_token').val(), 'practice_id': $('#practice_id').val()},
            success: function(data)
            {
                if (data == 'successsavinglocation')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Location Successfully Saved!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/location/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });


    $('#modalDiagnosis').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var practice_id = link.data('practice');
        var diagnosis_id = link.data('diagnosis');

        var modal = $(this);
        modal.find('.modal-footer #deleteLink').attr('href', '/admin/diagnosis/delete?diagnosis_id=' + diagnosis_id + '&practice_id=' + practice_id)
    })

    $('#modalInsurance').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var practice_id = link.data('practice');
        var insurance_id = link.data('insurance');

        var modal = $(this);
        modal.find('.modal-footer #deleteLink').attr('href', '/admin/insurance/delete?insurance_id=' + insurance_id + '&practice_id=' + practice_id)
    })

    $('#modalReason').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var practice_id = link.data('practice');
        var reason_id = link.data('reason');

        var modal = $(this);
        modal.find('.modal-footer #deleteLink').attr('href', '/admin/reason/delete?reason_id=' + reason_id + '&practice_id=' + practice_id)
    })

    $('#modalLocation').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var practice_id = link.data('practice');
        var location_id = link.data('location');

        var modal = $(this);
        modal.find('.modal-footer #deleteLink').attr('href', '/admin/location/delete?location_id=' + location_id + '&practice_id=' + practice_id)
    })

    $('#modalUser').on('show.bs.modal', function(event) {
        var link = $(event.relatedTarget);
        var user_id = link.data('user');
        var practice_id = link.data('practice');

        var modal = $(this);
        modal.find('.modal-footer #deleteLink').attr('href', '/admin/user/delete?user_id=' + user_id + '&practice_id=' + practice_id)
    })

    $('#create-user').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/admin/user/store",
            data: {
                'csrf_token': $('#csrf_token').val(),
                'name': $('#name').val(),
                'email': $('#email').val(),
                'password': $('#password').val(),
                'password_confirmation': $('#password_confirmation').val(),
                'practice_id': $('#practice_id').val()
            },
            success: function(data) {
                if (data == 'successsavinguser')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'User Successfully Saved!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/users/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });

    $('#update-user').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/admin/user/update",
            data: {
                'csrf_token': $('#csrf_token').val(),
                'name': $('#name').val(),
                'email': $('#email').val(),
                'user_id': $('#user_id').val(),
                'password': $('#password').val()
            },
            success: function(data) {
                if (data == 'successsavinguser')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'User Successfully Updated!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/admin/users/' + $('#practice_id').val(); }, 3000);
                } else
                {
                    $.growl({ title: '<strong>Errors:</strong> ', message: data
                    },{ //~ type: 'danger'
                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' },
                        delay: '6000'
                    });

                }
            }
        })
    });

    window.myDropzone;
    window.previewNode;
    window.previewTemplate;
    $('#referral_source_modal').on('hidden.bs.modal', function(e) {
        window.myDropzone.destroy();
        $('#practice_id').remove();
    });



    $('#referral_source_modal').on('show.bs.modal', function (event) {

        var link = $(event.relatedTarget);
        var practice_id = link.data('practice');
        $('<input>').attr({
            type: 'hidden',
            id: 'practice_id',
            name: 'practice_id',
            value: practice_id
        }).appendTo('#uploadForm');

        if (!window.previewNode)
        {
            window.previewNode = document.querySelector("#template");
            window.previewNode.id = "";
            window.previewTemplate = window.previewNode.parentNode.innerHTML;
            window.previewNode.parentNode.removeChild(window.previewNode);
        }

        window.myDropzone = new Dropzone('#referral_source_modal #uploadForm',{ // Make the whole body a dropzone
            url: "import/referral-source", // Set the url
            parallelUploads: 1,
            uploadMultiple: false,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewTemplate: window.previewTemplate,
            previewsContainer: "#previews",
            acceptedFiles: '.csv,.xls,.xlsx',
            dictDefaultMessage: "Click or Drop files here (csv, xls, xlsx)"
        });

        window.myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() { window.myDropzone.enqueueFile(file); };
        });

// Update the total progress bar
        window.myDropzone.on("totaluploadprogress", function(progress) {

        });

        window.myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts

            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
        });

        window.myDropzone.on('success', function(file, data) {
            if (data.error)
            {
                $('#previews strong.error').text(data.message);
            } else {
                $('#previews strong.success').text(data.message);
            }

        });

// Hide the total progress bar when nothing's uploading anymore
        window.myDropzone.on("queuecomplete", function(progress) {

        });

//Setup the buttons for all transfers
//The "add files" button doesn't need to be setup because the config
//`clickable` has already been specified.
//        document.querySelector("#actions .start").onclick = function(e) {
//            e.preventDefault();
//            window.myDropzone.enqueueFiles(window.myDropzone.getFilesWithStatus(Dropzone.ADDED));
//        };
//        document.querySelector("#actions .cancel").onclick = function() {
//            window.myDropzone.removeAllFiles(true);
//        };
    })



});
Dropzone.autoDiscover = false;