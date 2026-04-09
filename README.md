# online-pharmacy
Online pharmacy management website made using HTML, CSS, Bootstrap, MySQL, PHP and XAMPP Apache HTTP Server


<img width="960" alt="2021-12-25 (1)" src="https://user-images.githubusercontent.com/83854085/147381811-9eb8ef00-35d4-4ab4-a7f9-9048c001af9b.png">

<img width="960" alt="2021-12-25 (2)" src="https://user-images.githubusercontent.com/83854085/147381817-23d9b009-2288-4285-b867-8d5b79fe8c04.png">

<img width="960" alt="2021-12-25 (3)" src="https://user-images.githubusercontent.com/83854085/147381821-71173d20-3c9b-45aa-af40-7fc90fff00b5.png">

<img width="960" alt="2021-12-25 (4)" src="https://user-images.githubusercontent.com/83854085/147381822-8196835a-795c-4a2b-bf1b-b40b1d42829d.png">

<img width="960" alt="2021-12-25 (5)" src="https://user-images.githubusercontent.com/83854085/147381824-705c34fd-02a0-4679-a004-d8850e9d0c89.png">

<img width="960" alt="2021-12-25 (6)" src="https://user-images.githubusercontent.com/83854085/147381826-6b266cf1-f59f-4b8d-9dbc-52eb3a1e452a.png">

<img width="960" alt="2021-12-25 (7)" src="https://user-images.githubusercontent.com/83854085/147381827-12012552-662f-4715-befd-2522605b10d5.png">

<img width="960" alt="2021-12-25 (8)" src="https://user-images.githubusercontent.com/83854085/147381830-bd523807-229f-4ffb-811f-687d4f0b6984.png">

<img width="960" alt="2021-12-25 (9)" src="https://user-images.githubusercontent.com/83854085/147381831-46db1786-5754-40d4-86e2-744731124c23.png">

<img width="960" alt="2021-12-25 (10)" src="https://user-images.githubusercontent.com/83854085/147381833-5a6f13be-e0bf-4828-9cb5-7a9416b42af8.png">

<img width="960" alt="2021-12-25 (11)" src="https://user-images.githubusercontent.com/83854085/147381834-ac036911-2ab4-47ec-8728-f8793db07bc3.png">

<img width="960" alt="2021-12-25 (12)" src="https://user-images.githubusercontent.com/83854085/147381836-527b3d47-c331-42d8-a907-537029d4cc52.png">

<img width="960" alt="2021-12-25 (13)" src="https://user-images.githubusercontent.com/83854085/147381837-bfb12fc0-0d6f-4f83-89d9-1eb4c19efde4.png">

<img width="960" alt="2021-12-25 (14)" src="https://user-images.githubusercontent.com/83854085/147381838-346ab2f1-b8de-496d-a5bb-35e5391de527.png">

# Some features of this website

1	LOGIN FUNCTIONALITY\
•	Users are only allowed access to the admin section when they log in with correct username and password. Access remains until the user logs out of the session using the logout button.\
•	In case user tries to access the admin section without login through the use of a page’s URL name, he/she will be directed to the login page with an error message displayed.\
•	In case user tries to enter incorrect information in the admin login page, an error message will be displayed on the login page and they will not be allowed access.


2	ADMIN INFORMATION MAINTANENCE\
•	The admins’ passwords will be encrypted before they are stored in the database so that they can be stored securely.\
•	While updating admin password, user will have to enter the existing password and enter the new password twice to confirm it. If incorrect current password is given or the new password entered twice do not match, user will be unable to update the password.\
•	While updating admin name and username, the old information will be used as a placeholder in the update admin form.\
•	If a user tries to manipulate the website URL to get to the page to update/delete an admin record that does not exist, they will be directed to the manage admin page instead.


3	MEDICINE CATEGORY INFORMATION MAINTANENCE\
•	While adding a new category, the uploaded image gets stored with a randomly generated name so that different images uploaded with the same name do not override each other.\
•	 While adding a new category, if the user does not upload an image, an empty string will get stored in the image name attribute in the database table and message saying “Image Unavailable” will be displayed wherever the image is to be displayed.\
•	While updating an existing category, if the user uploads a new image, then the previous image will be deleted to save memory space and the new image will be saved instead.\
•	While updating an existing category, if the user does not upload a new image, the old image will remain unchanged.\
•	While updating medicine category, the old information will be used as a placeholder in the update medicine category form.\
•	If a user tries to manipulate the website URL to get to the page to update/delete an medicine category record that does not exist, they will be directed to the manage medicine category page instead.


4	MEDICINE INFORMATION MAINTANENCE\
•	While adding a new medicine, the uploaded image gets stored with a randomly generated name so that different images uploaded with the same name do not override each other.\
•	 While adding a new medicine, if the user does not upload an image, an empty string will get stored in the image name attribute in the database table and message saying “Image Unavailable” will be displayed wherever the image is to be displayed.\
•	While updating an existing medicine, if the user uploads a new image, then the previous image will be deleted to save memory space and the new image will be saved instead.\
•	While updating an existing medicine, if the user does not upload a new image, the old image will remain unchanged.\
•	While updating medicine, the old information will be used as a placeholder in the update medicine form.\
•	If a user tries to manipulate the website URL to get to the page to update/delete a medicine record that does not exist, they will be directed to the manage medicine category page instead.


5	ORDER INFORMATION MAINTANENCE\
•	Orders will get saved in the database when a customer fills the order form and clicks on confirm order button and will be displayed to the admin in the admin section.\
•	Admin can change the status of the order in the manage order section in the admin side of the system.\
•	If a user tries to manipulate the website URL to try to order a medicine that is not active or does not exist, they will be redirected to the home page instead.\
•	If a user tries to manipulate the website URL to change the order status of an order record that does not exist, they will get redirected to the manage orders page instead.


6	HOME PAGE DISPLAY\
•	The home page will show only those categories and medicines which are both active anf featured.


7	CATEGORIES PAGE DISPLAY\
•	The categories page will show only those categories which are active.


8	MEDICINES PAGE DISPLAY\
•	The medicines page will show only those medicines which are active.


9	SEARCH BAR FUNCTIONALITY\
•	The search bar results will display all those medicines whose name or description contains the keyword input into the search bar.\
•	If there are no search results available, the same will be displayed on the webpage instead of the medicine information cards.

