# heruko-test-app

## Steps to Create and Deploy a Simple PHP application to manage file on AWS S3 using Heruko PaaS

**Amazon S3 SDK Libirary** [Link](https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html)

**Heruko Amazon S3 Upload Tutorail** [Link](https://devcenter.heroku.com/articles/s3-upload-php)

# Useful command for preparing php environment to use heurko cli

**Install PHP 7.2**

 `sudo apt-get install php`

**Install Composer**

 `sudo apt-get install composer`

**Install Heurko CLI**

 `sudo snap install --classic heroku`

**Other installation method for Heroku CLI from Ubuntu Terminal**

 `curl https://cli-assets.heroku.com/install.sh | sh`

# Application setup

1. **Setup and Login into your free Heruko account**

2. Create a git repository (In this example we use GitHub) [Link](https://help.github.com/articles/create-a-repo/)

3. Clone the git repo into your local machine using the following command using the terminal 

   `git clone https://github.com/Badr1600/heruko-test-app.git`
   
4. Navigate to the cloned git repo

   `cd ~/heurko-test-app`
   
5. create a Heroku application using this command (make sure you already install the heroku cli) 
   
    **Note** When you invoke this command it ask you to login into your Heroku account, you can pass your account crednetials through the terminal or use web login to authenticate.
    
    **Check now your Heroku dashboard, a new app has been created**
    
    `heroku create`
   
   ![create app](https://user-images.githubusercontent.com/9883712/51892454-f927fc00-236f-11e9-9a6d-29f2743e674b.png)
   
   **Ignore the generated git repo link for now, we will create our own repo later**
   
   `sudo rm -rf .git`
   
6. Install the AWS SDK for PHP

   `composer require aws/aws-sdk-php:~2.6`
   
7. Add the vendor/ directory to your .gitingore

   `echo "vendor" >> .gitignore`
   
8. Commit the changes

   `git add composer.* .gitignore`
   
   `git commit -m "use aws/aws-sdk-php"`
   
9. **Important Step:** setup the AWS config vars (We will use this mechanism to dynamically read the AWS security keys (key ID and secret key)

   `heroku config:set AWS_ACCESS_KEY_ID=aaa AWS_SECRET_ACCESS_KEY=bbb S3_BUCKET=ccc`
   
10. Write down the php scripts to handle files upload/download (check files [upload](https://github.com/Badr1600/heruko-test-app/blob/master/index.php), [download](https://github.com/Badr1600/heruko-test-app/blob/master/list.php))

11. You can now add, commit and push this to Heroku

    `git add index.php`
   
    `git add download.php`
   
    `git commit -m "file upload and download test forms"`
   
    `git push`
   
12. Add the git repo into the created app:

    a. Select the application from Heroku dashboard then choose the deploy tab.
    
    b. Choose Github as for the Deployment method.
    
    c. Select the repo you just created and **Connect**
    
    d. **optional** Select automatic deploy where every push to the branch you specify here will deploy a new version of this app.
    
    e. finaly click on **Deploy Branch**
    
    
