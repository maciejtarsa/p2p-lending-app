// I will store the code returned from the server
// it is used for further authentication of requests
var stored_jwt = "";

// Cleares all non-menu items
function clearNonMenu() {
    var all = document.getElementsByClassName('non-menu');
    for (var i = 0 ; i < all.length; i++) {
        all[i].style.display = "none";
    }
    var content = document.getElementsByClassName('content');
    for (var i = 0 ; i < content.length; i++) {
        all[i].value="";
    }
    document.getElementById('block-change-details-notification').innerHTML = '';
    document.getElementById('block-my-game-table').innerHTML = ''
    
    
}

// Shows all menu items
function showAllMenu() {
    populateNotifications()
    var all = document.getElementsByClassName('menu');
    for (var i = 0 ; i < all.length; i++) {
        all[i].style.display = "";
    }
}

// Cleares all menu items
function clearAllMenu() {
    var all = document.getElementsByClassName('menu');
    for (var i = 0 ; i < all.length; i++) {
        all[i].style.display = "none";
    }
}

//Populates the values in notification block
function populateNotifications() {
    
    //clear the notification details
    document.getElementById('notifications-new-messages').innerHTML = '';
    // show number of unread messages
    $.get("http://mtarsa.heliohost.org/api/messages/unreadMessages.php",
              {'stored_jwt': stored_jwt},
              function(data){
            var response = JSON.parse(data);
            if (response.unread != null) {
                document.getElementById('notifications-new-messages').innerHTML = "<p>New messages: <b>" + response.unread + "</b></p>";
            } 
        }
        )
    // game requests
    // new feedback
    // games out
    // games in 
}

// Takes email and password and logins the user - shows the menu and hides other things
function login() {
    
    // set the global variables email and password to user input
    // stored_email = document.getElementById('login-email').value;
    // stored_password = document.getElementById('login-password').value;
    
    var email = document.getElementById('login-email').value;
    var password = document.getElementById('login-password').value;
    // access RESTful API to check the credentials
    // but only if credentials are not empty
    if (email == "" || password == "") {
         document.getElementById('block-login-notification').innerHTML = "<p>Email or password cannot be empty</p>";
    }
    else {
        $.get("http://mtarsa.heliohost.org/api/user_login/login.php",
              {'email':email, 'password':password},
              function(data){
            var response = JSON.parse(data);
            if (response.status == true) {
                // save the retrieved jwt value to global variable
                stored_jwt = response.jwt;
                document.getElementById('block-login-notification').innerHTML = "";
                document.getElementById('block-login').style.display = "none"; 
                document.getElementById('block-register').style.display = "none";
                showAllMenu();
                clearNonMenu();
                
            } 
            else {
                document.getElementById('block-login-notification').innerHTML = "<p>" + response.message + "</p>"
                // clear the values of stored credentials
                //var stored_email = null;
                //var stored_password = null;
            
                // clear the input for the login form
                document.getElementById('login-email').value = "";
                document.getElementById('login-password').value = "";
            }  
        }
        )
    }
}

// Opens the form for new registration
function registerShow() {
    
    document.getElementById('block-register').style.display = "";
    document.getElementById('block-login').style.display = "none";
    document.getElementById('block-login-notification').innerHTML = "";

}

function backToLogin() {
    document.getElementById('block-register').style.display = "none";
    document.getElementById('block-login').style.display = "";
    document.getElementById('block-login-notification').innerHTML = "";
}

// Checks that the password inputted are matching
function checkPasswordEntry() {
  if (document.getElementById('register-password').value !=
    document.getElementById('register-password-repeat').value) {
    alert('Passwords are not matching');
    document.getElementById('register-password').value='';
    document.getElementById('register-password-repeat').value='';
    return false;
  }
}

// Takes new user details and adds that user to the database
function register() {
    
    // for good measure, check that the passwords are matching
    if (checkPasswordEntry() == false) {
        //stop the execution
    }
    else {
        
        
        //create variables for each of the elements to be posted
        var email = document.getElementById('register-email').value;
        var password = document.getElementById('register-password').value;
        var name = document.getElementById('register-name').value;
        var address1 = document.getElementById('register-address1').value;
        var address2 = document.getElementById('register-address2').value;
        var town = document.getElementById('register-town').value;
        var postcode = document.getElementById('register-postcode').value;;
        
        // Then post them to the database
        $.post(
        "http://mtarsa.heliohost.org/api/user_login/register.php",
        {
            email: email, password: password, name: name, address1: address1, address2: address2, town: town, postcode: postcode
        },
        function(data) {
           var response = JSON.parse(data);
            if (response.status == true) {
                document.getElementById('block-login-notification').innerHTML = "<p>You have now been registered. Please log in.</p>";
                document.getElementById('block-login').style.display = "";
                document.getElementById('block-register').style.display = "none";
            }
            else{
                // email already exists - alert the user
                document.getElementById('block-login-notification').innerHTML = "<p>" + response.message + "</p>";
            }
        });
    }
}

// Logs the user out and goes back to login page
function logout() {
    
    // log the user out by clearing their login credentials
    //var stored_email = null;
    //var stored_password = null;
    var jwt = null;
    
    // clear the input for the login form
    document.getElementById('login-email').value = "";
    document.getElementById('login-password').value = "";
    
    // and take them back to the login page
    document.getElementById('block-login').style.display = "";
    
    document.getElementById('block-login-notification').innerHTML = "<p>You have been logged out</p>";

    clearAllMenu();
    clearNonMenu();
}

// Open the list of games the user currently has on the database, shows them in alphabetical order and allows the user to look through them, edit their details and add more
function showMyGames() {

    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-my-games').style.display = "";
    document.getElementById('my-games-form').style.display = "none";
    document.getElementById('my-games-notification').innerHTML = "";
    
    getMyGamesIds();

}

// A function to retrieve a list of ID of games belonging to the user
function getMyGamesIds() {
    
    // reset the table div to nothing
    document.getElementById('block-my-game-table').innerHTML = ''; 
    
    // no longer synchronous
    // in this function, I used a synchronus request, otherwise it would have retrieved data too quickly for the other requests
    $.ajax({
        url:"http://mtarsa.heliohost.org/api/game/readAll.php",
        type: "get",
        async: true,
        data: {'stored_jwt': stored_jwt},
        success: function(data){
        var response = JSON.parse(data);
        // set up a table object
        var tableHTML = "<table id = 'my-games-table' width=90%><tr><th class='my-games-table'>Name</th><th class='my-games-table'>Status</th><th class='my-games-table'></th></tr>";
        if (response.status == true) {
            // for each game returned
            $.each(response.game, function(index,value) {
                // add a row of data to the table
                var tableRow = "<tr>"; 
                tableRow += "<td class='my-games-table'>" + value.name + "</td>";
                tableRow += "<td class='my-games-table'>" + value.status + "</td>";
                tableRow += "<td class='my-games-table'><button onclick='displayMyGames("+ value.game_id+ ")'>View</button></td>";
                
                tableHTML += tableRow;
            });
            tableHTML += "</table>";
            document.getElementById('block-my-game-table').innerHTML = tableHTML; 
        } 
        else{
            // display relevant message
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    }});
} 

// display the details of a game
function displayMyGames(my_game_id) {
   
    // hide any messages
    document.getElementById('my-games-notification').innerHTML = "";
    if (my_game_id) {
 $.get("http://mtarsa.heliohost.org/api/game/readOne.php",
    {'stored_jwt': stored_jwt, game_id: my_game_id},
    function(data){
        var response = JSON.parse(data);
        if (response.status == true) {
            // display the form
            document.getElementById('my-games-form').style.display = '';
            // input data into fields
            document.getElementById('my-games-id-field').value = response.game_id;
            document.getElementById('my-games-name').value = response.name;
            document.getElementById('my-games-description').value = response.description;
            document.getElementById('my-games-value').value = response.value;
            document.getElementById('my-games-status').value = response.game_status;
        }
       else {
           // hide the form
           document.getElementById('my-games-form').style.display = 'none';
           // display relevant message
           document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
       }
    }
    )
    }
    else {
        // display relevant message
        document.getElementById('my-games-notification').innerHTML = "<p>No games found!</p>";
    }
}

// Clears the form for games and lets the user add details of a new one
function newGame() {
    
    // ensure the form is visible
    document.getElementById('my-games-form').style.display='';
    // empty the fields on the screen
    document.getElementById('my-games-id-field').value = '';
    document.getElementById('my-games-name').value = '';
    document.getElementById('my-games-description').value = '';
    document.getElementById('my-games-value').value = '';
    document.getElementById('my-games-status').value = '';

}

// Adds the details of the new game to the db
function saveGame() {
    
    //create variables for each of the elements to be posted
    var id = document.getElementById('my-games-id-field').value;
    var name = document.getElementById('my-games-name').value;
    var description = document.getElementById('my-games-description').value;
    var value = document.getElementById('my-games-value').value;
    var status = document.getElementById('my-games-status').value;
    
    // if id field is empty
    if (id.length == 0) {
        // Then post them to the database
        $.post( "http://mtarsa.heliohost.org/api/game/create.php",
    {
    stored_jwt: stored_jwt, name: name, description: description, value: value, status: status
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // refresh the table
            getMyGamesIds();
            // populate games with that game displayed and then display relevant message
            displayMyGames(response.game_id);
            // display relevant message
            // added after previous functions to override it
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
            
        }
        else{
            // alert the error
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
    // change the variable for new game back to false for any subsequent requests
    new_game = false;    
    }
    // otherwise update the details of the current game
    else{
                $.post( "http://mtarsa.heliohost.org/api/game/update.php",
    {
    stored_jwt: stored_jwt, game_id: id, name: name, description: description, value: value, status: status
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // refresh the table
            getMyGamesIds();
            // refresh the display for the current game
            displayMyGames(response.game_id);
            // show relevanty message
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
        else{
            // alert the error
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
    }
}

// Deletes the currently displayed game
function deleteGame() {
    // create a variable for the id of the game to be deleted
    var id = document.getElementById('my-games-id-field').value;
    // check that the user definitely wants to delete it
    var response = confirm("Are you sure you want to delete this game?");
    // if they said yes
    if (response == true) {
        $.post( "http://mtarsa.heliohost.org/api/game/delete.php",
    {
    stored_jwt: stored_jwt, game_id: id
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // refresh the table
            getMyGamesIds();
            // hide the form
            document.getElementById('my-games-form').style.display='none';
            // show relevant message
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
        else{
            // alert the error
            document.getElementById('my-games-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
    }
}

// Opens the list of games belonging to the user currently on loan, showing the loans in chronological order. In this block the game can be returned - by adding the details of the date of the return and deposit information.
function showOtherGames() {
    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-other-loans').style.display = "";
}

// Changes to previous loan
function otherLoansPrevLoan() {
    
    // TO DO
    // move to the previous loan
    
}

// Changes to next loan
function otherLoansNextLoan() {
    
    // TO DO
    // move to the next loan
    
}

// Saves the current record to the db
function otherLoansSaveLoan() {
    
    // TO DO
    // save the details of the loan to the db
    
}

// Opens chat with the user who borrowed this game
function otherLoansMessageUser() {
    
    // TO DO
    // open the messages with the owner of the game
    
    
    // for now I will hide everything and show messages
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-messages').style.display = "";
}

// Show current/potential borrowers feedback below
function otherLoansShowFeedback() {
    
    // TO DO
    // populate feedback based on the displayed user
    
    document.getElementById('block-other-loans-show-feedback').style.display = "";
    
}

// Show a form below to provide feedback to the borrower
function otherLoansProvideFeedback() {
    
    document.getElementById('block-other-loans-show-feedback').style.display = "none";
    document.getElementById('block-other-loans-provide-feedback').style.display = "";

}

// Saves the feedback to the db
function otherLoansSubmitFeedback() {
    
    // TO DO
    // submit the current feedback to the db
    
    // for now I will just go back to the menu
    clearNonMenu();
    
}

// Show the games the current user has borrowed from others
function showMyLoans() {
    
    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-my-loans').style.display = ""; 
}

// Changes to the previous loan
function myLoansPrevLoan() {
    
    // TO DO
    // move to the previous loan
    
}

// Changes to the next loan
function myNextLoan() {
    
    // TO DO
    // move to the next loan
    
}

// Save the details of the current loan
function myLoansSaveLoan() {
    
    // TO DO
    // save the details of the loan to the db
    
}

// Open the chat with the owner of the game
function myLoansMessageUser() {
    
    // TO DO
    // open the messages with the owner of the game
    
    
    // for now I will hide everything and show messages
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-messages').style.display = "";
}

// Shows the feedback of the owner of the game
function myLoansShowFeedback() {
    
    document.getElementById('block-my-loans-show-feedback').style.display = "";
    
}

// Shows a form below to provide feedback to the owner
function myLoansProvideFeedback() {
    
    document.getElementById('block-my-loans-show-feedback').style.display = "none";
    document.getElementById('block-my-loans-provide-feedback').style.display = "";

}

// Submits the feedback for the owner of the game
function myLoansSubmitFeedback() {
    
    // TO DO
    // submit the current feedback to the db
    
    // for now I will just go back to the menu
    clearNonMenu();
    
}

// Populates a list of available games in the area so that user can choose if he would like to request to borrow any of them.
function showSearchGames() {
    
    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-search-games').style.display = ""; 
    
    // ajax for getting the list of available games
     $.get("http://mtarsa.heliohost.org/api/search/read.php",
              {'stored_jwt': stored_jwt},
              function(data){
            var response = JSON.parse(data);
            if (response.status == true) {
            // set up a table object
            var available_games_div = "<div id='available-games-display' width=90%>";
            // for each game returned
            $.each(response.game, function(index,value) {
                // add a row of data to the table
                var divID = 'searchGamesMessage' + value.game_id;
                var divRow = "<b>Game: " + value.name + "</b><br>Description: " +value.description + "<br>Owner: " + value.owner + "<br>Rating: " + value.feedback + "<br>Postcode: " + value.postcode + "<br>Distance: " + value.distance + " miles<br><button class='submit-button' onclick='searchGamesRequestOpen("+ value.game_id + ", \"" + value.name +"\", \"" + value.owner +"\", \"" + value.email +"\")'>Request game</button><br><button class='submit-button' onclick='searchGamesMessage("+ value.game_id + ", \"" + value.email + "\")'>Message owner</button><br><div id='" + divID + "'></div><br>";
            available_games_div += divRow;
            });
            available_games_div += "</div>";
            document.getElementById('block-search-games').innerHTML = available_games_div;
        } 
        else {
            // display relevant message
            document.getElementById('block-search-notification').style.display="";
            document.getElementById('block-search-notification').innerHTML = "<p>" + response.message + "</p>";
        }
        }
        )
    
}

// Opens up a form when a user can put in the details of the request and send it to the owner
function searchGamesRequestOpen(game_id, name, owner, email) {
    
    //document.getElementById('block-search-games').style.display="none";
    
    document.getElementById('block-search-games').innerHTML = "<p>You are requesting " + name + " from " + owner + "</p>";
    document.getElementById('search-games-request-id-field').value = game_id;
    document.getElementById('search-games-request-email').value = email;
    document.getElementById('search-games-request-name').value = name;
    document.getElementById('block-search-games-request').style.display="";

}

// Opens up a message window with the owner of the game
function searchGamesMessage(game_id, other_email) {
    
    // message the owner of the game
    // popoulate the div under the game details with a window to message and send messages
    var idValue = 'searchGamesMessage' + game_id;
    document.getElementById(idValue).innerHTML = "<form method='post' id='search-messages-send-form'><p><input type='text' id='search-messages-send-form-text' required placeholder='Type your message here...'></p><p><button type='button' id='messages-send-button' onclick='searchGamesSendMessage(" + game_id + ", \"" + other_email + "\")'>Send message</button></p></form>";

}

// Send a message to the owner of the game
function searchGamesSendMessage(game_id, other_email) {
    //create variables for each of the elements to be posted
    var typed_message = document.getElementById('search-messages-send-form-text').value;
    var idValue = 'searchGamesMessage' + game_id;
    
    // Then post them to the database
    $.post( "http://mtarsa.heliohost.org/api/messages/post.php",
    {
    stored_jwt: stored_jwt, other_email: other_email, typed_message: typed_message
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // replace the form with a message
            document.getElementById(idValue).innerHTML = "<b><center>Message Sent</center></b>";
        }
        else{
            // alert the error
            document.getElementById(idValue).innerHTML += "<p>Message Not sent!</p>";
        }
    });

}
    
// Creates a new loan with status requested and the details provided
// Also - automatically takes the user to messages with the owner of the game
function searchGamesRequest() {
    
    // TO DO
    // submit the current request to borrow this game
    
    // first, set up some variables
    var success = false;
    var game_id = document.getElementById('search-games-request-id-field').value;
    var startDate = document.getElementById('search-games-request-start-date').value;
    var duration = document.getElementById('search-games-request-duration').value;
    var comments = document.getElementById('search-games-request-comments').value;
    var ownerEmail = document.getElementById('search-games-request-email').value;
    var  name = document.getElementById('search-games-request-name').value;
    var message = "Hi, I just requested " + name + " starting from " + startDate + ". If you are happy for me to borrow it, please accept it and we can agree day and time to meet.";
    
    // then sent the ajax request
    $.post( "http://mtarsa.heliohost.org/api/search/submit.php",
    {
    stored_jwt: stored_jwt, game_id: game_id, startDate: startDate, duration: duration, comments: comments
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            
            var success = true;
        }
        else{
            // alert the error
                    document.getElementById('block-search-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
        
    // if the request has been sent successfuly
    
        // post a message to the owner about the request on behalf of the sender
        $.post( "http://mtarsa.heliohost.org/api/messages/post.php",
        {
            stored_jwt: stored_jwt, other_email: ownerEmail, typed_message: message
        },
        function(data) {
        var response = JSON.parse(data);
            if (response.status == true) {
                // clear the div for searches
                document.getElementById('block-search-games').innerHTML = "";
                // hide the request form
                document.getElementById('block-search-games-request').style.display = "none";
                // populate a notification with confirmation
                document.getElementById('block-search-notification').style.display="";
                document.getElementById('block-search-notification').innerHTML = "<p>The request has been sent to the owner.</p>";
                }
                else {
                // alert the error
                document.getElementById('block-search-notification').innerHTML = "<p>" + response.message + "</p>";
                }
        });
    
}

// Shows all messages with other users
function showMessages() {
    
    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-messages').style.display = "";
    
    // populate a list of chats with different people
    messagesPopulateList();
    // clear the message display
    document.getElementById("messages-message").innerHTML = '';
}

// create a list of all chats a user has with others and insert it into a drop down
function messagesPopulateList() {
    
    // Get dropdown element from DOM
    var dropdown = document.getElementById("messages-list");
    // clear all elements
    for (i = dropdown.options.length -1; i>=1; i--) {
        dropdown.options[i] = null;
    }
    dropdown[0] = new Option("Please select...", null);
    
    
    // get a list of emails the user has conversations with from the server
    $.ajax({
        url:"http://mtarsa.heliohost.org/api/messages/readEmails.php",
        type: "get",
        async: false,
        data: {'stored_jwt': stored_jwt},
        success: function(data){
        var response = JSON.parse(data);
        // set up a table object
        if (response.status == true) {
            // display the drown down
            document.getElementById('messages-drop-down').style.display="";
        
            // for each user returned
            $.each(response.user, function(index,value) {
                var el = document.createElement("option");
                el.value = value.messages_email;
                el.label = value.name + ", " + value.unread + " unread";
                dropdown.add(el);
                
                // the below works as well, but populates the text again once selected
                //dropdown[dropdown.length] = new Option(value.name + ", " + value.unread + " unread", value.messages_email);
            });
            // show the chat with the laters person
            //messageDisplayChat();
        } 
        else{
            document.getElementById('messages-drop-down').style.display="none";
            // display relevant message
            document.getElementById('block-messagess-notification').style.display="";
            document.getElementById('block-messagess-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    }});
}

// display the chat with selected user
function messagesDisplayChat() {
    
    //clear the display
    document.getElementById('messages-message').innerHTML = '';
    document.getElementById('block-messagess-notification').innerHTML = '';
    
    var other_email = document.getElementById('messages-list').value;
    
    // set up a variable to determine whether there are any unread messages
    //var unread_true = false;
    
    if (other_email != "null") {
    // get messages between two users
    $.ajax({
        url:"http://mtarsa.heliohost.org/api/messages/readMessages.php",
        type: "get",
        async: true,
        data: {'stored_jwt': stored_jwt, 'other_email': other_email},
        success: function(data){
        var response = JSON.parse(data);
        // set up a table object
            
        
        if (response.status == true) {
            // show form for sending msg
            document.getElementById('messages-send-form').style.display="";
            // set up a table object
            var message_div = "<div id='messages-message-display' width=90%>";
            // for each game returned
            $.each(response.message, function(index,value) {
                // add a row of data to the table
                if (value.name=="Me"){
                    var divRow="<div style='text-align: right';>";
                }
                else {
                    var divRow="<div style='text-align: left;'>";
                } 
                divRow += "<b>" + value.name + "</b><br><i>" +value.timestamp + "</i><br>";
                if (value.unread == "1") {
                    divRow+="<b>"+ value.message + "</b><br></div>";
                    //var unread_true = true;
                    // mark this message as read
                    $.ajax({
                        url:"http://mtarsa.heliohost.org/api/messages/markRead.php",
                    type: "post",
                    async: false,
                    data: {'stored_jwt': stored_jwt, 'id': value.id},
                    success: function(data){
                    var response = JSON.parse(data);
                    if (response.status == true) {
                    //do nothing
                    }
                    else{
                    // alert the error
                    document.getElementById('block-messagess-notification').style.display="";
                    document.getElementById('block-messagess-notification').innerHTML = "<p>" + response.message + "</p>";
                    }
                    }});
                }
                else {
                    divRow+=value.message + "<br></div>";
                }
                message_div += divRow;
            });
            message_div += "</div>";
            document.getElementById('messages-message').innerHTML = message_div;
        } 
        else {
            // display relevant message
            document.getElementById('block-messagess-notification').style.display="";
            document.getElementById('block-messagess-notification').innerHTML = "<p>" + response.message + "</p>";
            
        }
    }});
        
    // in case there were any unread, repopulate the list of email addresses
    messagesPopulateList();
    // and select the one currently displayed
    var dropdown = document.getElementById("messages-list");
    for (var i=0; i<=dropdown.length; i++) {
        if(dropdown[i].value === other_email) {
            dropdown[i].selected = true;
            break;
        }
    }
    //var index = document.getElementById
    //dropdown.options[other_email].selected = true;
        //document.getElementById("messages-list").value = other_email;  
    }
}

// Send the message a user has typed in    
function messagesSendMessage() {
    
    //create variables for each of the elements to be posted
    var other_email = document.getElementById('messages-list').value;
    var typed_message = document.getElementById('messages-message-type').value;
    
    // Then post them to the database
    $.post( "http://mtarsa.heliohost.org/api/messages/post.php",
    {
    stored_jwt: stored_jwt, other_email: other_email, typed_message: typed_message
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // refresh the page
            messagesDisplayChat();
            // clear the messages type form
            document.getElementById('messages-message-type').value = "";
        }
        else{
            // alert the error
            document.getElementById('block-messagess-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
    
}

// Shows the current details of the user
function showChangeDetails() {
    
    // hide all non-menu elements
    clearNonMenu();
    // show only the one referring to the current button
    document.getElementById('block-change-details').style.display = ""; 
    
    // access RESTful API to get the details of the user
    $.get("http://mtarsa.heliohost.org/api/user_details/read_details.php",
          {'stored_jwt': stored_jwt},
              function(data){
            var response = JSON.parse(data);
            if (response.status == true) {
                document.getElementById('change-details-name').value = response.name;
                document.getElementById('change-details-address1').value = response.address1;
                document.getElementById('change-details-address2').value = response.address2;
                document.getElementById('change-details-town').value = response.town;
                document.getElementById('change-details-postcode').value = response.postcode;
            }
    })    
}

// Changes the details of the user
function changeDetailsSubmit() {
    
    //create variables for each of the elements to be posted
    var name = document.getElementById('change-details-name').value;
    var address1 = document.getElementById('change-details-address1').value;
    var address2 = document.getElementById('change-details-address2').value;
    var town = document.getElementById('change-details-town').value;
    var postcode = document.getElementById('change-details-postcode').value;
    
    
    // Then post them to the database
    $.post( "http://mtarsa.heliohost.org/api/user_details/update_details.php",
    {
    stored_jwt: stored_jwt, name: name, address1: address1, address2: address2, town: town, postcode: postcode
    },
    function(data) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // confirm with message to the user
            document.getElementById('block-change-details-notification').innerHTML = "<p>" + response.message + "</p>";
        }
        else{
            // alert the error
            document.getElementById('block-change-details-notification').innerHTML = "<p>" + response.message + "</p>";
        }
    });
}

var app = {
    initialize: function () {
        this.getGoing();
    },
    getGoing: function () {
        function SimpleApp() {
            
            // something or maybe nothing here??
        }
        this.mysimpleApp = new SimpleApp();
    }
};
app.initialize();