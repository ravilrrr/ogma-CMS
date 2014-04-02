### Introduction

Allows you to add a Twitter feed to your site

### Setup

First off, you'll need to create a [Twitter App](https://dev.twitter.com/apps) 

Go to the configuration page for the plugin and enter the following information.  

- Twitter Name, the feed name you wish to display. 
- Consumer Key
- Consumer Secret
- Access Key
- Access Secret


### Usage 

####Shortcode 

Insert this shortcode into a snippet or page/blog. 

    [twitterfeed num="5" /]

Where *num* is the number of Tweets to return


####Function
Raw JSON data can be returned by using 

    Twitterfeed::returnTweet($num);