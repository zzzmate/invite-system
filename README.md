Invite System written in PHP with a working Register / Login system

- Basically with MySQL u can count invites to a person.
- Invites basically when the user invites someone to your website: e.g: yourwebsite.com/invite=1.
- Then it adds +1 invite to his name.

The code is clean, probably everywhere.
I meade it in 2 hours, so if the code somewhere does not makes sense, or not clean, then it's because of that.

Features:

- Every 5 minute can the user invite somebody. <- If he invites somebody before the cooldown expires, then it just redirects the user to register.php.
- In the index.php u can:
                        - Copy ur invite code.
                        - Get ur current mail.
                        - Get ur username (Hello, {user})
                        - Registered users (counted)
                        - Logout
