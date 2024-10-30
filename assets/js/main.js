(function (NioApp, $) {
    "use strict";
    $(document).ready(function () {

        $.fn.serializeObject = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };

        var loader = $('<span/>', { 'class': 'spinner-grow spinner-grow-sm' });

        // Image upload 
        $('body').on('click', '.file-upload', function (e) {
            e.preventDefault();
            var button = $(this),
                custom_uploader = wp.media({
                    title: 'Insert image',
                    library: {
                        // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                        type: 'image'
                    },
                    button: {
                        text: 'Use this image' // button label text
                    },
                    multiple: false
                }).on('select', function () { // it also has "open" and "close" events
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('.file-upload').val(attachment.id);
                    $('.file-label').text(attachment.filename);
                }).open();

        });

        // Init DataTable 
        function hrpInitTable(table, data, disableOrdering = [], check = [], searching = true, paging = true, info = true,) {
            NioApp.DataTable(table, {
                'processing': true,
                'serverSide': true,
                searching: searching, paging: paging, info: info,
                'order': [],
                'ajax': {
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                },
                'deferRender': true,
                'lengthMenu': [10, 25, 50, 100],
                columnDefs: [
                    { targets: "_all", className: 'nk-tb-col', },
                    { targets: check, className: 'nk-tb-col-check', },
                    {
                        'targets': disableOrdering, /* column index [0,1,2,3]*/
                        'orderable': false, /* true or false */
                    }
                ],
                rowCallback: function (row) {
                    $(row).addClass('nk-tb-item');
                }
            });
        }

        // Show Success Alert.
        function hrpShowSuccessAlert(message, formId) {
            NioApp.Toast(message, "success", { position: "top-right" });
        }

        function hrpDisplayFormErrors(response, formId) {
            if (response.data && $.isPlainObject(response.data)) {
                $(formId + " :input").each(function () {
                    var input = this;
                    $(input).removeClass("invalid");
                    if (response.data[input.name]) {
                        var errorSpan = '<span class="invalid">' + response.data[input.name] + "</span>";
                        $(input).addClass("invalid");
                        $(errorSpan).insertAfter(input);
                    }
                });
            } else {
                NioApp.Toast(response.data, "error", { position: "top-center" });
            }
        }

        // Display Form Error.
        function hrpDisplayFormError(response, formId, button) {
            button.prop('disabled', false);
            var errorSpan = '<div class="text-danger mt-2"><span>' + response.status + '</span>: ' + response.statusText + '<hr></div>';
            $(errorSpan).insertBefore(formId);
        }

        // Before Submit button.
        function hrpBeforeSubmit(button) {
            $('span.invalid').remove();
            $(".invalid").removeClass("invalid");
            button.prop('disabled', true);
            loader.prependTo(button);
            return true;
        }

        function hrpComplete(button) {
            button.prop("disabled", false);
            loader.remove()
        }

        // DataTable checkbox select all.
        $('#bulk-select').click(function (e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });

        function deleteSingleRow(event, data) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7367f0',
                cancelButtonColor: '#82868b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function (data) {
                            if (data.success) {
                                Swal.fire('Success!', data.message, 'success');
                                location.reload();
                            } else {
                                Swal.fire('Error!', data.message, 'error');
                            }
                        }
                    })
                }
            })
        }

        function deleteSingleRowDatatable(event, data) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7367f0',
                cancelButtonColor: '#82868b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function (data) {
                            if (data.success) {
                                Swal.fire('Success!', data.data, 'success');
                                $('.dataTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error!', data.data, 'error');
                            }
                        }
                    })
                }
            })
        }

        $(document).on('click', '.hrp-save-btn', function (event) {
            event.preventDefault();
            var saveFormId = '.hrp-save-form';
            var saveForm = $(saveFormId);
            var saveBtn = $('.hrp-save-btn');
            saveForm.ajaxSubmit({
                beforeSubmit: function (arr, $form, options) {
                    return hrpBeforeSubmit(saveBtn);
                },
                success: function (response) {
                    if (response.success) {
                        hrpShowSuccessAlert(response.data.message, saveFormId);
                    } else {
                        hrpDisplayFormErrors(response, saveFormId);
                    }
                },
                error: function (response) {
                    hrpDisplayFormError(response, saveFormId, saveBtn);
                },
                complete: function (event, xhr, settings) {
                    hrpComplete(saveBtn);
                }
            });
        });

        hrpInitTable($('#employees'), { action: 'hrp-fetch-employees' }, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [0]);
        hrpInitTable($('#attendances-employees'), { action: 'hrp-fetch-attendances-employees' }, [0, 1, 2, 3], []);
        hrpInitTable($('#holidays'), { action: 'hrp-fetch-holidays' }, [0, 1, 2, 3, 4, 5, 6, 7], [0]);
        hrpInitTable($('#announcements'), { action: 'hrp-fetch-announcements' }, [0, 1, 2, 3, 4, 5, 6], [0]);
        hrpInitTable($('#shifts'), { action: 'hrp-fetch-shifts' }, [0, 1, 2, 3, 4, 5, 6], [0]);
        hrpInitTable($('#designations'), { action: 'hrp-fetch-designations' }, [0, 1, 2, 3, 4], [0]);
        hrpInitTable($('#departments'), { action: 'hrp-fetch-departments' }, [0, 1, 2, 3, 4, 5], [0]);
        hrpInitTable($('#reports'), { action: 'hrp-fetch-reports' }, [0, 1, 2, 3], []);

        // employee data tables
        hrpInitTable($('#emp-announcements'), { action: 'hrp-fetch-emp-announcements' }, [0, 1, 2, 3, 4], []);

        function hrpAttendanceTable() {
            var data = $('.hrp-attendance-form').serializeObject();
            data['from_table'] = true;
            hrpInitTable($('#attendances'), data, [0], [], false, false, false);
        }
        hrpAttendanceTable();

        $(document).on('click', '#hrp-filter-attendance-btn', function (event) {
            event.preventDefault();
            var getFormId = '.hrp-attendance-form';
            var getForm = $(getFormId);
            var getExpenseBtn = $('#hrp-filter-attendance-btn');
            getForm.ajaxSubmit({
                beforeSubmit: function (arr, $form, options) {
                    return hrpBeforeSubmit(getExpenseBtn);
                },
                success: function (response) {
                    if (response) {
                        $('#attendances').DataTable().clear().destroy();
                        hrpAttendanceTable();
                    } else {
                        hrpDisplayFormErrors(response, getFormId);
                    }
                },
                error: function (response) {
                    hrpDisplayFormError(response, getFormId, getExpenseBtn);
                },
                complete: function (event, xhr, settings) {
                    hrpComplete(getExpenseBtn);
                }
            });
        });

        // Send Test email 
        $(document).on('click', '.send-test-email', function() {
			var button = $(this);
			var nonce = button.data('nonce');
			var template = button.data('template');
			var to = button.parent().find('.send-test-email-to').val();
			var data = {
				'to': to,
				'template': template,
				'nonce': nonce,
				'action': 'send-test-email'
			};
			$.ajax({
				data: data,
				url: ajaxurl,
				type: 'POST',
				beforeSend: function(xhr) {
					return hrpBeforeSubmit(button);
				},
				success: function(response) {
					if(response.success) {
                        Swal.fire('Success!', response.data.message, 'success');
					} else {
						if ( response.data ) {
                            Swal.fire('Error!', response.data, 'error');
						}
					}
				},
				error: function(response) {
					if ( response.data ) {
                        Swal.fire('Error!', response.data, 'error');
					}
				},
				complete: function(xhr) {
					hrpComplete(button);
				}
			});
		});


        function saveFormData(fromId, formButton) {
            var getFormId = fromId;
            var getForm = $(getFormId);
            var getFormBtn = $(formButton);
            getForm.ajaxSubmit({
                beforeSubmit: function (arr, $form, options) {
                    return hrpBeforeSubmit(getFormBtn);
                },
                success: function (response) {
                    if (response.success) {
                        hrpShowSuccessAlert(response.data.message, getFormId);
                        setTimeout(function () { location.reload(true); }, 2000);
                    } else {
                        hrpDisplayFormErrors(response, getFormId);
                    }
                },
                error: function (response) {
                    hrpDisplayFormError(response, getFormId, getFormBtn);
                },
                complete: function (event, xhr, settings) {
                    hrpComplete(getFormBtn);
                }
            });
        }

        // settings form save
        $(document).on('click', '#hrp-email-template-btn', function (event) {
            saveFormData('#hrp-email-template-form', '#hrp-email-template-btn')
            event.preventDefault();
        });

        $(document).on('click', '#hrp-sms-template-btn', function (event) {
            saveFormData('#hrp-sms-template-form', '#hrp-sms-template-btn')
            event.preventDefault();
        });

        $(document).on('click', '#hrp-general-setting-btn', function (event) {
            saveFormData('#hrp-general-setting-form', '#hrp-general-setting-btn')
            event.preventDefault();
        });
        
        $(document).on('click', '#hrp-notification-btn', function (event) {
            saveFormData('#hrp-notification-form', '#hrp-notification-btn')
            event.preventDefault();
        }); 

        // employee Dashboard buttons
        $(document).on('click', '#hrp-checkin-btn', function (event) {
            saveFormData('#hrp-checkin-form', '#hrp-checkin-btn')
            event.preventDefault();
        });

        $(document).on('click', '#hrp-checkout-btn', function (event) {
            saveFormData('#hrp-checkout-form', '#hrp-checkout-btn')
            event.preventDefault();
        });

        $(document).on('click', '#hrp-breakin-btn', function (event) {
            saveFormData('#hrp-breakin-form', '#hrp-breakin-btn')
            event.preventDefault();
        });

        $(document).on('click', '#hrp-breakout-btn', function (event) {
            saveFormData('#hrp-breakout-form', '#hrp-breakout-btn')
            event.preventDefault();
        });

        // delete department
        $('.dataTable').on('click', '.delete-department', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-department' };
            deleteSingleRowDatatable(event, data);
        })

        // delete designation
        $('.dataTable').on('click', '.delete-designation', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-designation' };
            deleteSingleRowDatatable(event, data);
        })

        // delete shift
        $('.dataTable').on('click', '.delete-shift', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-shift' };
            deleteSingleRowDatatable(event, data);
        })

        // delete employee
        $('.dataTable').on('click', '.delete-employee', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-employee' };
            deleteSingleRowDatatable(event, data);
        })

        // delete announcement
        $('.dataTable').on('click', '.delete-announcement', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-announcement' };
            deleteSingleRowDatatable(event, data);
        })

        // delete holiday
        $('.dataTable').on('click', '.delete-holiday', function (event) {
            let data = { id: $(this).data('id'), nonce: $(this).data('nonce'), action: 'hrp-delete-holiday' };
            deleteSingleRowDatatable(event, data);
        })

    });

    // announcement show options.
    $(function () {
        $('#send_to').change(function () {
            $('.announcement-options').hide();
            $('#' + $(this).val()).show();
        });

    });

    $(function () {
        $('#mail-send-method').change(function () {
            if ($(this).val() == 'smtp') {
                $('.wp-mail').hide();
                $('.smtp-mail').show();
            } else {
                $('.wp-mail').show();
                $('.smtp-mail').hide();
            }
        });
    });

    if ($('.time').length) {
        // Clock and date. 
        var time = document.querySelector(".time");
        var dateTime = document.querySelector(".date-time");

        function updateClock() {
            // Get the current time, day , month and year
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var day = now.getDay();
            var date = now.getDate();
            var month = now.getMonth();
            var year = now.getFullYear();

            // store day and month name in an array
            var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            // format date and time
            hours = hours % 12 || 12;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            date = date < 10 ? "0" + date : date;

            // display date and time
            var period = hours < 12 ? "AM" : "PM";
            time.innerHTML = hours + ":" + minutes + ":" + seconds + " " + period;
            dateTime.innerHTML = dayNames[day] + ", " + monthNames[month] + " " + date + ", " + year;
        }

        updateClock();
        setInterval(updateClock, 1000);
    }

    // Clipboard copy
    $(document).ready(function () {
        $(".copy").click(function (event) {
            var $tempElement = $("<input>");
            $("body").append($tempElement);
            $tempElement.val($(this).closest(".copy").text()).select();
            document.execCommand("Copy");
            $tempElement.remove();
        });
    });

})(NioApp, jQuery);
