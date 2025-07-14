<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}?>
<style>
  nav {
    background: #6c63ff;
    color: white;
    padding: 12px 20px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
  .navbar-container {
    max-width: 960px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }
  .navbar-left a {
    font-weight: bold;
    font-size: 18px;
    color: white;
    text-decoration: none;
  }
  .navbar-right a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    font-size: 15px;
  }
  .navbar-right span {
    font-size: 15px;
    font-weight: 500;
  }
</style>

