<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vending Machine</title>

  <!-- Material Components Web (MDC) Google Styles -->
  <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
  
  <!-- Material Design Icons -->
  <link rel="stylesheet" href="https://googleapis.com">
  
  <style>
    :root {
      --mdc-theme-primary: #6200ee;
      --mdc-theme-secondary: #03dac6;
      background-color: #f8f9fa;
      font-family: Roboto, sans-serif;
    }
    body { margin: 0; padding: 0; }
    .app-container { padding: 24px; max-width: 1200px; margin: 0 auto; }
    
    /* Display Screen Layout */
    .machine-display {
      background-color: #212121;
      color: #fff;
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      margin-bottom: 24px;
      font-family: monospace;
    }
    .log-item { padding: 4px 0; font-size: 14px; }
    .log-error { color: #ffb74d; }
    .log-success { color: #a5d6a7; }
    .log-info { color: #81d4fa; }

    /* Flex Layout Grid */
    .dashboard-grid { display: flex; gap: 24px; flex-wrap: wrap; }
    .dashboard-col { flex: 1; min-width: 320px; }
    
    /* MDC Card Adjustments */
    .mdc-card { padding: 20px; border-radius: 12px; margin-bottom: 16px; background-color: #fff; }
    .card-title { margin-top: 0; margin-bottom: 20px; font-weight: 500; }
    
    /* Grid Chips for Items */
    .items-list { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
    .custom-chip {
      display: inline-flex;
      align-items: center;
      background-color: #e0e0e0;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
    }
    .custom-chip .delete-icon {
      margin-left: 8px;
      cursor: pointer;
      font-size: 16px;
      color: #757575;
    }
    .custom-chip .delete-icon:hover { color: #d32f2f; }

    /* Inline Form Row Controls */
    .form-inline-row { display: flex; gap: 12px; align-items: center; margin-top: 16px; }
    .form-inline-row .mdc-text-field { flex: 1; }
    
    /* Global Error Tooltip (MDC Snackbar) */
    .mdc-snackbar {
      position: fixed;
      bottom: 24px;
      right: 24px;
      left: auto;
      transform: none;
    }
  </style>
</head>
<body>
    <div style='width:60%; margin: auto'>
        <div id="app-container">
