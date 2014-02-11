<?php
require_once('php/ap-config.php');
require_once('php/ap-db.php');
require_once( 'php/ap-session.php' );
require_once( 'php/ap-redirect.php' );
ap_user_session_start();
$authuser = ap_user_session_auth();
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Ace+ - Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        padding-bottom: 40px;
        background: #444 url(assets/img/aceplus_cal_bg.jpg);
        background-attachment: fixed;
      }
      .acebox {
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .modal-ace {
        width: 650px;
        margin-left: -375px;
    }
    </style>
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

</head>
<body>
    <?php include_once('common/header_menu.php'); ?>
<div class="container acebox">
    <div class="row-fluid">
        <h2><center>Find the course you are taking now</center></h2>
        <span class="span3"></span>
        <input class="input-medium search-query typeahead span6" type="text" data-provide="typeahead" autocomplete="off" placeholder="Search...">
        <span class="span3"></span>
        <button id="searchBtn" class="btn btn-info"><i class="icon-search icon-white"></i></button>
    </div>
    <div>
    <table id="result" class="table table-striped">
        <tr><th>Department</th><th>Number</th><th>Name</th><th>Operation</th></tr>
    </table>
    </div>
<!--     <div class="pagination pagination-centered">
        <ul>
            <li class="disabled"><a href="#">&laquo;</a></li>
            <li class="active"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">&raquo;</a></li>
        </ul>
    </div> -->
</div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $('input.typeahead').typeahead({
            minLength: 3,
            items: 50,
            source: function (query, process) {
                $.post("thor.php",
                {
                    act: "course_search",
                    ap_search: query
                },
                function (result, status) {
                    if (result.success) {
                        process(result.options);
                    }
                }, "json");
            }
        });
        $('button#searchBtn').click(function (e) {
            var course = $('input.typeahead').val();
            console.log(course);
            $.post("thor.php",
                {
                    act: "course_list",
                    ap_search: course
                },
                function (result, status) {
                    if (result.success) {
                        $("table#result").html("<tr><th>Department</th><th>Number</th><th>Name</th><th>Operation</th></tr>");
                        generate(result.options);
                    }
                },"json"
            );
        });
    });

    function generate (data) {
        var item, html;
        for (var i = 0; i < data.length; i++) {
            item = data[i];
            html = '<tr><td>'+[item['dept'], item['number'], item['name'], '<a onclick="go2view('+item['id']+')">View</a><span>&nbsp;</span><a onclick="go2group('+item['id']+')">Group</a>'].join('</td><td>')+'</td></tr>';
        ;
        $("table#result").append(html);
        };
    }

    function go2fork (cid) {
        $.get("thor.php",
            {
                act: "forkall",
                cid: cid
            },
            function (result, status) {
                if (result.success) {
                    alert("OK");
                }
            },"json"
        );
    }

    function go2view (cid) {
        window.location.assign("/assign.php?cid="+cid);
    }

    function go2group (cid) {
        window.location.assign("/group.php?cid="+cid);
    }
    </script>
</body>
</html>