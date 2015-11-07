# Token Login 

This Codes describes a module to allow Login with a token alone (as opposed 
to username+password).

To achieve this we do the following: username and token are semantically 
swapped as
* we can not have one username having multiple passwords.
* but we can have different user names always having the same password!
The token which technically is the username will serve as the password.

## Changes to the regular login process

1. Extend the regular login module (`ModuleTokenlogin extends \ModuleLogin`)
2. Provide a special login form that takes care of the changes. This form 
will not have a password field. 
3. Set the (`POST`-)value so the regular login module will be happy.
4. Register a method for the `importUser` hook that creates a new member. 
See classes/MyHooks::importFromTokenlist() for an example.