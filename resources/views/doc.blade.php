<!DOCTYPE html>
<html>
<head>
    <title>MansaPay Docs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1rem;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }

        h2 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .menu,
        .submenu {
            cursor: pointer;
            margin-bottom: 10px;
        }

        .submenu-item {
            padding: 10px;
            background-color: #e9ecef;
            margin-bottom: 10px;
            border-radius: 5px;
            display: none;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        td {
            background-color: #f8f9fa;
        }

        .menu-item,
        .menu-items {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">MansaPay Docs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#users">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#transaction">Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#utility">Utility</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1>MansaPay Documentation</h1>

    <h4>Base URL - </h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <h2>FormData Example</h2>
            <div>
                <code>
                    <pre>
                        <i>
                            var formData = new FormData(document.getElementById('include the id of your form'));
                            fetch("{baseurl}/{endpoint}",{
                              "method" : "POST",
                              "headers" : {
                                <!-- "accessToken" : "{access token provided by the server}" -->
                              },
                              "body" : formData
                            }).then(response => response.json())
                            .then(data => {
                                console.log(data);
                                //Work on the data here
                            })
                        </i>
                    </pre>
                </code>
            </div>
        </div>
        <div class="col-md-6">
            <h2>JsonData Example</h2>
            <div>
                <code>
                    <pre>
                        <i>
                            fetch("{baseurl}/{endpoint}",{
                              "method" : "POST",
                              "headers" : {
                                "Content-Type" : "application/json; charset=utf-8",
                                <!-- "accessToken" : "{access token provided by the server}" -->
                              },
                              "body" : JSON.stringify(JsonData)
                            }).then(response => response.json())
                            .then(data => {
                                console.log(data);
                                //Work on the data here
                            })
                        </i>
                    </pre>
                </code>
            </div>
        </div>
    </div>

    <section id = "members">
        <h1>Members page</h1>
        <section id="users">
            <h2>Users</h2>
            <button type="button" class="menu btn btn-primary w-100 text-start">Users</button>
            <div class="menu-items">
                <div id="verify_transaction" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Verify Transaction for Registration</button>
                    <div class="submenu-item">
                        <h4>Mode of sending data - FormData</h4>
                        <h6>endpoint - /customer/authenticate/verify_transaction.php</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>fullname</td>
                                    <td>The full name of the user provided in the form</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>reference</td>
                                    <td>The reference of the transaction after a successful transaction</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>phone</td>
                                    <td>The phone number of the user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>The email of the user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>plan</td>
                                    <td>The plan the user subscribes to</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="register_user" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Register User</button>
                    <div class="submenu-item">
                        {{-- <h4>Mode of sending data - FormData</h4> --}}
                        <h6>endpoint - /api/register</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>firstname</td>
                                    <td></td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>lastname</td>
                                    <td></td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>email set by the user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>phone</td>
                                    <td>phone set by the user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td>Password for the user account</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id = "verify" class = "menu-item">
                    <button type = "button" class="submenu btn btn-outline-primary w-100 text-start">Verify OTP</button>
                    <div class = "submenu-item" style = "display: none;">
                        <h4>Mode of sending data - Jsondata</h4>
                        <h6>endpoint - /api/otp</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The userid given to the user during registration</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>otp</td>
                                <td>The otp will be sent to the mail during registration</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id = "login" class = "menu-item">
                    <button type = "button" class="submenu btn btn-outline-primary w-100 text-start">Login User</button>
                    <div class = "submenu-item" style = "display: none;">
                        <h4>Mode of sending data - Jsondata</h4>
                        <h6>endpoint - /login</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td>The email of the user</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>password</td>
                                <td>The password the user will input user, not less than 10 characters</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id = "getprofile" class = "menu-item">
                    <button type = "button" class = "submenu btn btn-outline-primary w-100 text-start">Get the profile of a user</button>
                    <div class = "submenu-item" style = "display: none;">
                        <h4>Mode of sending data - Jsondata</h4>
                        <h6>endpoint - api/dashboard</h6>
                        <h6>Method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The userid of the the user whose profile is being viewed</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>Authorization</td>
                                <td>Header parameter. This is the access token provided by the server for individual user</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>
        </section>

        <section id="transaction">
            <h2>Transaction</h2>
            <button type="button" class="menu btn btn-primary w-100 text-start">Transaction</button>
            <div class="menu-items">
                <div id="add_todo" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Make Transaction</button>
                    <div class="submenu-item">
                        {{-- <h4>Mode of sending data - FormData</h4> --}}
                        <h6>endpoint - /maketransaction</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Authorization</td>
                                    <td>Header parameter. This is the access token provided by the server for individual user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>userid</td>
                                    <td>The id of the user. Provided by the server after successful login</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>amount</td>
                                    <td>The amount of money specified by a user to be sent or received after a successful transaction</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>utility</td>
                                    <td>reason for payment or objects paid for</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>status</td>
                                    <td>This is whether the user is being credited or debited. Represented as either + or -</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>description</td>
                                    <td>Description of the payment made by the user</td>
                                    <td>true</td>
                                </tr>
                                <tr>
                                    <td>reference</td>
                                    <td>The reference of the transaction after a successful transaction</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id = "gettransaction" class = "menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Get transaction data</button>
                    <div class = "submenu-item" style = "display: none;">
                        <h4>Mode of sending data - Jsondata</h4>
                        <h6>endpoint - /gettransaction</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>Authorization</td>
                                <td>Header parameter. This is the access token provided by the server for individual user</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The id of the user whose transaction data is to be checked</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section id="utility">
            <h2>Utility</h2>
            <button type="button" class="menu btn btn-primary w-100 text-start">Utility</button>
            <div class="menu-items">
                <div id = "getutility" class = "menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Get Utility</button>
                    <div class = "submenu-item" style = "display: none;">
                        {{-- <h4>Mode of sending data - Jsondata</h4> --}}
                        <h6>endpoint - /getutility</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>Authorization</td>
                                <td>Header parameter. This is the access token provided by the server for individual user</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id = "addutility" class = "menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Add Utility</button>
                    <div class = "submenu-item" style = "display: none;">
                        {{-- <h4>Mode of sending data - Jsondata</h4> --}}
                        <h6>endpoint - /getutility</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>Authorization</td>
                                <td>Header parameter. This is the access token provided by the server for individual user</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>utility</td>
                                <td>The utility that the user wishes to add</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section id="partner">
            <h2>Accountability Partner</h2>
            <button type="button" class="menu btn btn-primary w-100 text-start">Accountability Partner</button>
            <div class="menu-items">
                <div id="add_todo" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Add Partner</button>
                    <div class="submenu-item">
                        {{-- <h4>Mode of sending data - FormData</h4> --}}
                        <h6>endpoint - /addpartner</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Authorization</td>
                                    <td>Header parameter. Provided by the server for individual user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>userid</td>
                                    <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>trackerid</td>
                                    <td>The id of the user to be added as tracker</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id = "getpartners" class = "menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Get Partners</button>
                    <div class = "submenu-item" style = "display: none;">
                        <h4>Mode of sending data - Jsondata</h4>
                        <h6>endpoint - /getpartners</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Parameters</th>
                                <th>Description</th>
                                <th>Optional</th>
                            </tr>
                            <tr>
                                <td>Authorization</td>
                                <td>Header parameter. This is the access token provided by the server for individual user</td>
                                <td>false</td>
                            </tr>
                            <tr>
                                <td>userid</td>
                                <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                <td>false</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="add_todo" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Remove Partner</button>
                    <div class="submenu-item">
                        {{-- <h4>Mode of sending data - FormData</h4> --}}
                        <h6>endpoint - /removepartner</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Authorization</td>
                                    <td>Header parameter. Provided by the server for individual user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>userid</td>
                                    <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>trackerid</td>
                                    <td>The id of the user to be added as tracker</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="add_todo" class="menu-item">
                    <button type="button" class="submenu btn btn-outline-primary w-100 text-start">Search Users</button>
                    <div class="submenu-item">
                        {{-- <h4>Mode of sending data - FormData</h4> --}}
                        <h6>endpoint - /search</h6>
                        <h6>method - POST</h6>
                        <h3>Parameters required</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Description</th>
                                    <th>Optional</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Authorization</td>
                                    <td>Header parameter. Provided by the server for individual user</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>userid</td>
                                    <td>The id of the user. It will be provided by the server when the user logs in successfully</td>
                                    <td>false</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>The name of the user to be added as tracker</td>
                                    <td>false</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <script>
        document.querySelectorAll('.menu').forEach(menu => {
            menu.addEventListener('click', () => {
                const menuItems = menu.nextElementSibling;
                if (menuItems) {
                    menuItems.style.display = menuItems.style.display === 'none' ? 'block' : 'none';
                }
            });
        });

        document.querySelectorAll('.submenu').forEach(submenu => {
            submenu.addEventListener('click', () => {
                const submenuItem = submenu.nextElementSibling;
                if (submenuItem) {
                    submenuItem.style.display = submenuItem.style.display === 'none' ? 'block' : 'none';
                }
            });
        });
    </script>
</body>
</html>
