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

  <title><?= $title; ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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

  <link rel="icon" type="image/x-icon" href="../../dist/img/kcs-bg.webp">
</head>
<style>
  body {
    font-size: 16px;
    line-height: 1.5;
    font-family: 'POPPINS', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }

  h1 {
    font-size: 48px;
  }

  h2 {
    font-size: 36px;
  }

  h3 {
    font-size: 28px;
  }

  p {
    font-size: 16px;
  }


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
  th.delete-kanban,
  th.th-width {
    width: 120px;
  }

  table {
    width: 100%;
    table-layout: auto;
    font-size: 14px;
  }

  td {
    white-space: nowrap;
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

  .active .icon-image {
    filter: brightness(0) invert(1);
  }

  .icon-image {
    filter: none;/
  }

  input[type='text'],
  input[type='password'],
  input[type='number'],
  input[type='search'],
  input[type='date'] {
    border-radius: 10px;
  }

  .add-btn {
    display: block;
    position: fixed;
    bottom: 50px;
    right: 30px;
    z-index: 99;
    border: none;
    outline: none;
    background-color: #19323C;
    color: white;
    padding: 10px 17px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 50px;
  }

  .active {
    /*#E3ECF3*/
    border-bottom: 4px solid #19323C !important;
    color: #19323C !important;
    font-weight: 600;
  }

  .nav-item:hover>.nav-link{
    background-color: #244656 !important;
    color: #ffffff !important;
    border-radius: 3px;
  }

  .activeBtn {
    background-color: #19323C !important;
    color: #fff !important;
    border-radius: 10px;

    &:hover {
      background-color: #2D5C8B !important;
      color: #fff !important;

    }
  }

  .submitBtn {
    background-color: #19323C !important;
    color: #fff !important;
    border-radius: 10px;

    &:hover {
      background-color: #1F6C98 !important;
      color: #fff !important;

    }
  }

  .actionBtn {
    background-color: #55A6F1 !important;
    color: #fff !important;
    border-radius: 10px;

    &:hover {
      background-color: #19323C !important;
      color: #fff !important;

    }
  }

  .delBtn {
    background-color: #D27484 !important;
    color: #fff !important;
    border-radius: 10px;

    &:hover {
      background-color: #DD5755 !important;
      color: #fff !important;

    }
  }

  .signOutBtn {
    background-color: #f3f3f3 !important;
    /*#000EA4*/
    border-bottom: 2px solid #19323C !important;
    color: #111 !important;
  }

  .add-btn:hover {
    background-color: #276DBD;
  }

  .addBtn {
    background-color: #19323C !important;
    color: #fff !important;
    border-radius: 15px;
    border: none;

    &:hover {
      background-color: #276DBD;
    }
  }


  .thead-bg {
    background-color: #ffffff !important;
    color: var(--secondary) !important;
    font-weight: 600;
  }

  .exportBtn {
    background-color: #fff !important;
    color: #1D2935 !important;
    border-radius: 10px;
    border: 2px solid #ccc;
    font-weight: 500;

    &:hover {
      background-color: #f3f3f3 !important;
      color: #19323C !important;

    }
  }

  .generateBtn {
    background-color: #19323C !important;
    color: #ffffff !important;
    border-radius: 10px;
    border: 2px solid #ccc;
    font-weight: 500;
  }

  .importModalBtn {
    background-color: #E3ECF3 !important;
    color: #1D2935 !important;
    border-radius: 10px;
    border: 2px solid #ccc;
    font-weight: 500;
  }

  .red-highlight {
    /* background-color: #D27484; */
    color: #D27484;
  }

  .search_key {
    width: 50%;
    transition: width 0.3s ease;
  }

  .search_key:focus-within {
    width: 100%;
  }

  .nav-title {
    font-size: 23px;
    text-transform: uppercase;
  }

  /* Default navbar background color */
  #sticky-navbar {
    background-color: #ffffff;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;

  }

  #sticky-navbar.scrolled {
    background-color: #19323C;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    color: #ffffff !important;
  }

  #sticky-navbar.scrolled .nav-link {
    color: #f4f6f8 !important;
    /* Ensure nav links also change color */
  }

  #sticky-navbar.scrolled .nav-link.active {
    border-bottom: 4px solid #ffffff !important;
  }
</style>