var userObj = new Vue({
    el: '#user',
    data: {
        user: {},
        delete_text: null,
        userId: null,
        currentUser: null,
        currentUser: null,
        msg: {error: null, success: null},
    },

    ready: function(){
        this.getUser();
    },

    methods: {
        getUser: function(){
            $.get( window.baseurl + "/api/user", function( result ) {
                userObj.user = result;
            });
        },
        getAllUsers: function(){
            $.get( window.baseurl + "/api/users", function( result ) {
                userObj.users = result.data;
            });
        },
        showCreateForm: function(){
          this.msg.success = null;
          this.msg.error = null;
          $(".new-user").show();
          $(".new-user .first").focus();
        },
        create: function(new_user, update){

            update = update || false;

            $.ajax({
              type: 'POST',
              url: window.baseurl + "/api/create_account",
              data: new_user,
              error: function(e) {
                var response = jQuery.parseJSON(e.responseText);
                $('.new-user .status-msg').text("")
                                            .removeClass('success-msg')
                                            .addClass("error-msg")
                                            .text(response.message);                
                return false;
              },

              success: function(result){                        
                $('.new-user .status-msg').text("")
                                            .removeClass('remove-msg')                                      
                                            .addClass("success-msg")
                                            .text(result.message);
                            
                if (update == true){
                    $('.user-table tbody').append("<td class='text-center'><img src='"+result.data.avatar+"' style='width:30px'> </td> <td> "+result.data.name+" </td> <td> "+result.data.username+" </td> <td> "+result.data.email+" </td> <td> "+result.data.role_name+" </td><td><a class='btn btn-default' title='Under Process'><i class='ion-loop'></i></a></td>"); 
                }

                new_user.name = null;
                new_user.username = null;
                new_user.email = null;
                $('.popup-form.new-user').find('input[type=text],textarea,select').filter(':visible:first').focus();
              }
            }); 
        },
        startUserEditMode: function(userIndex){
            this.msg.success = null;
            this.msg.error = null;

            $(".popup-form.update-user").show();
            $(".popup-form.update-user").find('input[type=text],textarea,select').filter(':visible:first').focus();
        },
        update: function(){            
            var data = this.user;
            var user_id=data.user.id;

            $.ajax({
                type: "POST",
                url: window.baseurl + "/api/user/"+data.user.id,
                data: data.user,
                success: function(result){                    
                    $('#user'+user_id).html("<td class='text-center'><img src='"+result.data.avatar+"' style='width:30px'> </td> <td> "+result.data.name+" </td><td> "+result.data.username+" </td> <td> "+result.data.email+" </td> <td> "+result.data.role_name+" </td><td><a class='btn btn-default' title='Under Process'><i class='ion-loop'></i></a></td>"); 
                    $('.update-user .status-msg').text("").removeClass('remove-msg').addClass("success-msg") .text(result.message);
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    $('.update-user .status-msg').text("").removeClass('success-msg').addClass("error-msg").text(response.message);
                    return false;
                }
            });
        },
        delete: function(currentUser,userId){
            this.currentUser=currentUser;
            showSheet();
            makePrompt("Are you sure you want to disable the user: "+currentUser.name+"?","This action is irreversible","No now", "Yes");

            $("#cancel-btn").click(function(){
                closePrompt();
            });

            $("#confirm-btn").click(function(userId){

                $.ajax({
                    type: "DELETE",
                    url: window.baseurl + "/api/user/"+currentUser.id,
                    success: function(result){
                         document.location.href="/users";
                    },
                    error: function(e){
                        closePrompt();
                    }
                });
            });
        }
    }

});