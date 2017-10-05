# **Documentation**

## **Setup**
Run the following command in MAPP or WAPP's **app** folder: <br>
`git clone https://github.com/CS2102-Team1/carpoolerz.git`

Go to the directory **apache2/conf/bitnami/bitnami-apps-prefix.conf** and replace <br>
`Include "/Applications/mappstack-7.0.23-0/apps/demo/conf/httpd-prefix.conf"`
<br> with <br>
`Include "/Applications/mappstack-7.0.23-0/apps/carpoolerz/conf/httpd-prefix.conf"`

Copy and paste the **conf** files from the demo folder to the carpoolerz folder and replace all instances of the word **demo** with the word **carpoolerz**.

## **Team Version Control Guidelines**
1. Each team member is to clone this repository into the relevant apps folder on their local machine. 

2. Create your own development branch (i.e. name_dev). This will result in 1 local branch (i.e. the carpoolerz folder in your local drive) and 1 remote branch(the online branch on github). REMINDER: It is not necessary to fork out of this project. 

3. All changes are to be made on your local development branch. After changes, commit to your own remote branch first. 

4. After that, update this master branch(i.e. our main source code) with the changes from your development branch by creating a pull request (i.e. request the master branch to pull changes from our development branch). The pull request will then be approved on this github repo by an admin(i.e. one of us). 

5. WARNING: Do not push straight into master by using `git push origin master` or equivalent.

4. To update your development branch with changes from this master branch, perform a Git Pull by entering `git pull --rebase`


