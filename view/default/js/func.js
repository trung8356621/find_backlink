function login_box() {
    $('#Log').toggle(function () {
        $('#Log_Box').slideDown(500);
    }, function () {
        $('#Log_Box').slideUp(500);
    });
}

function S_Process() {
    $('#S_Box').submit(function (event) {
        event.preventDefault();
        var val = $(this).find('input[name="link"]').val();
        if (val.length > 0) {
            if (val.indexOf('http://') !== -1) {
                val = val.replace('http://', '');
            } else if (val.indexOf('https://') !== -1) {
                val = val.replace('https://', '');
            }
            location.href = url + 'search/referring-page/?q=' + encodeURIComponent(val);
        } else {
            $('#S_Con').empty().load(url + 's-false');
        }
    });
}
count = 0;
function login() {
    $('form#Log_Box').submit(function (event) {
        event.preventDefault();
        var email = $(this).find('input#Email').val();
        var pass = $(this).find('input#Pass').val();
        var rem = $(this).find('input#Rem:checked').val();
        var action = $(this).attr('action');
        if (email != '' && pass != '') {
            $.ajax({
                url: action,
                type: 'POST',
                data: {
                    id: email,
                    pass: pass,
                    rem: rem
                },
                success: function (data) {
                    if (data === 'ok') {
                        location.reload();
                    } else {
                        if (count >= 5) {
                            alert('ss');
                        }
                    }
                }
            });
        } else {
            alert(count);
            if (count >= 5) {
                window.location.href = url + 'pricing';
                count = 0;
            }
            count++;
        }
    });
}

function delete_td(obj, event, id) {
    event.preventDefault();
    var url = $(obj).attr('href');
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            if (data == 'ok') {
                alert('Delete success');
                location.reload();
            } else {
                alert('Delete false');
            }
        }
    });
}


function send_bl_tbl(cur, like, type, num_bl, red) {
    $.ajax({
        type: 'POST',
        url: url + 'bl-tbl',
        data: {ipv4: ipv4, like: like, cur: cur, type: type, num_bl: num_bl},
        success: function (data) {
            $('.bl_info_tb').empty().html(data);
            red != '' ? window.history.pushState('', '', red.attr('href')) : '';
        }
    });
}

function next(){
    var cur='';
    send_bl_tbl(cur, like, type, num_bl, '');
}

function submit(cur, type, num_bl) {
    $('#filter').submit(function (event) {
        event.preventDefault();
        var like = $('#bl_In').val();
        send_bl_tbl(cur, like, type, num_bl, '');
    });
}


function respond() {
    var width = $(window).width();
    if (width > 787) {
        $('body').css({'font-size': 15});
        $('input[type="text"]').css({'font-size': '10px'});
        $('#S_Box').css({left: '15%', width: '50%'});
    } else if (width > 540) {
        $('body').css({'font-size': 10});
        $('#S_Box').css({left: '15%', width: '80%'});
    } else if (width > 428) {
        $('body').css({'font-size': 8});
        $('#S_Box').css({left: '20%', width: '80%'});
        $('input[type="text"]').css({'font-size': '7px'});
    } else {
        $('body').css({'font-size': 6});
        $('#S_Box').css({left: '15%', width: '80%'});
    }
}

function respond_res() {
    respond();
    $(window).resize(function () {
        respond();
    });
}