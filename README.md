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
1. Each team member is to clone this repository into the relevant apps folder on their local machine and create a development branch.

2. All changes are to be made on the development branch, with pull requests made to the master branch or between branches for transfer of new features.

3. `git pull --rebase` is to be used to grab changes from the remote repository, and transfer of changes are to be made strictly through pull requests, not `git merge`.
