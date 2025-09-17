<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Material Icons</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background-color: #f9fafb;
        }
        .icon-test {
            display: inline-flex;
            align-items: center;
            margin: 10px;
            padding: 10px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .icon-test .material-icons {
            margin-right: 8px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Material Icons Test</h1>
    
    <h2>Icons Used in Permission Matrix:</h2>
    <div class="icon-test">
        <span class="material-icons text-green-600">check</span>
        <span>check - Granted Permission</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-red-600">close</span>
        <span>close - Denied Permission</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-red-600">block</span>
        <span>block - Super Admin Only</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-gray-400">remove</span>
        <span>remove - Not Applicable</span>
    </div>
    
    <h2>Icons Used in User Verification:</h2>
    <div class="icon-test">
        <span class="material-icons text-green-600">verified_user</span>
        <span>verified_user - Verified/Active User</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-orange-600">pending</span>
        <span>pending - Unverified/Pending User</span>
    </div>
    
    <h2>Alternative Icons for Pending:</h2>
    <div class="icon-test">
        <span class="material-icons text-orange-600">schedule</span>
        <span>schedule - Alternative for pending</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-orange-600">hourglass_empty</span>
        <span>hourglass_empty - Alternative for pending</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-orange-600">access_time</span>
        <span>access_time - Alternative for pending</span>
    </div>
    <div class="icon-test">
        <span class="material-icons text-orange-600">pending_actions</span>
        <span>pending_actions - Alternative for pending</span>
    </div>
    
    <h2>Checkbox Test:</h2>
    <div style="background: white; padding: 20px; border: 1px solid #e5e7eb; border-radius: 4px; margin: 10px 0;">
        <h3>Default Checkbox (should be blue with white checkmark):</h3>
        <label style="display: block; margin: 10px 0;">
            <input type="checkbox" checked> Checked checkbox
        </label>
        <label style="display: block; margin: 10px 0;">
            <input type="checkbox"> Unchecked checkbox
        </label>

        <h3>Permission Matrix Style:</h3>
        <div class="permission-matrix">
            <label style="display: block; margin: 10px 0;">
                <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> Matrix checkbox checked
            </label>
            <label style="display: block; margin: 10px 0;">
                <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"> Matrix checkbox unchecked
            </label>
        </div>

        <h3>Table Context (like permission matrix):</h3>
        <table style="border-collapse: collapse; width: 100%; margin: 10px 0;">
            <tr>
                <td style="border: 1px solid #e5e7eb; padding: 10px;">
                    <input type="checkbox" checked> Checked in table
                </td>
                <td style="border: 1px solid #e5e7eb; padding: 10px;">
                    <input type="checkbox"> Unchecked in table
                </td>
            </tr>
        </table>

        <h3>Form Context:</h3>
        <form>
            <label style="display: block; margin: 10px 0;">
                <input type="checkbox" checked> Form checkbox checked
            </label>
            <label style="display: block; margin: 10px 0;">
                <input type="checkbox"> Form checkbox unchecked
            </label>
        </form>
    </div>
    
    <h2>Icon Availability Test:</h2>
    <div style="background: white; padding: 20px; border: 1px solid #e5e7eb; border-radius: 4px; margin: 10px 0;">
        <p>If you can see all the icons above properly, then Material Icons is loaded correctly.</p>
        <p>If any icon shows as a square or text, then that icon is not available in the current Material Icons version.</p>
    </div>
</body>
</html>
