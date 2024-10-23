<?php
//SESSION
include '../../process/login.php';

if (!isset($_SESSION['username'])) {
  header('location:../../');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?> - Admin</title>


  <link rel="icon" href="../../dist/img/kcs-bg.webp" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../../dist/css/font.min.css">

  <link rel="stylesheet" href="../../plugins/DataTables/datatables.min.css">
  <link rel="stylesheet" href="../../dist/css/datatable/dataTables.dataTables.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Sweet Alert -->
  <link rel="stylesheet" href="../../plugins/sweetalert2/dist/sweetalert2.min.css">

  <link rel="stylesheet" href="../../plugins/datatable/dist/dataTables.dataTables.min.css">


  <style>
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #536A6D;
      width: 50px;
      height: 50px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(1080deg);
      }
    }

    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #332D2D;
    }

    .active {
      background-color: #275DAD !important;
      /*#000EA4*/
      border-bottom: 2px solid #ffffff !important;
      color: #fff;

    }

    .activeBtn {
      background-color: #275DAD !important;
      color: #fff !important;
      border-radius: 16px;

      &:hover {
        background-color: #1F6C98 !important;
        color: #fff !important;

      }
    }

    .submitBtn {
      background-color: #275DAD !important;
      color: #fff !important;
      border-radius: 16px;

      &:hover {
        background-color: #1F6C98 !important;
        color: #fff !important;

      }
    }

    .actionBtn {
      background-color: #55A6F1 !important;
      color: #fff !important;
      border-radius: 16px;

      &:hover {
        background-color: #1F6C98 !important;
        color: #fff !important;

      }
    }
    .delBtn {
      background-color: #D27484 !important;
      color: #fff !important;
      border-radius: 16px;

      &:hover {
        background-color: #1F6C98 !important;
        color: #fff !important;

      }
    }

    .signOutBtn {
      background-color: #f3f3f3 !important;
      /*#000EA4*/
      border-bottom: 2px solid #275DAD !important;
      color: #111 !important;
    }

    .active .icon-image {
      filter: brightness(0) invert(1);
    }

    .icon-image {
      filter: none;/
    }

    table th {
      /* padding: 10px; */
      white-space: nowrap;
      text-align: left;
    }

    th.part-code {
      width: 150px;
    }

    th.part-name {
      width: 200px;
    }

    th.min-lot,
    th.max-usage,
    th.max-plan,
    th.teams,
    th.takt-time,
    th.conveyor-speed,
    th.usage-hour,
    th.lead-time,
    th.safety-inv,
    th.req-kanban,
    th.issued-pd,
    th.add-kanban,
    th.delete-kanban {
      width: 120px;
    }

    table {
      width: 100%;
      table-layout: auto;
    }

    .add-btn {
      display: block;
      /* Visible by default */
      position: fixed;
      /* Fixed/sticky position */
      bottom: 50px;
      /* Place the button at the bottom of the page */
      right: 30px;
      /* Place the button 30px from the right */
      z-index: 99;
      /* Make sure it appears on top of other elements */
      border: none;
      /* Remove borders */
      outline: none;
      /* Remove outline */
      background-color: #275DAD;
      /* Dark background */
      color: white;
      /* White text */
      padding: 10px 17px;
      /* Some padding */
      font-size: 16px;
      /* Increase font size */
      cursor: pointer;
      /* Pointer/hand icon on hover */
      border-radius: 50px;
      /* Rounded corners */
    }

    .add-btn:hover {
      background-color: #276DBD;
      /* Darker background on hover */
    }

    input[type='text'],
    input[type='password'],
    input[type='number'],
    input[type='date'] {
      border-radius: 15px;
    }
  </style>
</head>

<!-- sidebar-collapse sidebar-mini-->
<div id="preloader" class="preloader flex-column justify-content-center align-items-center">
  <img class="" src="../../dist/img/loader.gif" alt="logo" height="60" width="60">
</div>

<body class="hold-transition sidebar-mini layout-fixed ">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-light" style="background-color: #306BAC;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>