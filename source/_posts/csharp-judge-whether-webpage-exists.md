---
layout: post
title: How to judge whether a webpage existed in a fast way
date: 2016/3/9 08:00:00
tags:
- C#
---

Recently I am coding a app for viewing newspaper. Because there are no API, I have to manually find whether the page ever existed. So I try to look on the Internet, and find a try catch way to judge it:


```csharp
HttpWebResponse response = null;
 var request = (HttpWebRequest)WebRequest.Create(/* url */);
 request.Method = “HEAD”;

try{
 response = (HttpWebResponse)request.GetResponse();
}
catch (WebException ex){
 /* A WebException will be thrown if the status of the response is not `200 OK` */
}
finally{
 // Don’t forget to close your response.
 if (response != null)
 {
    response.Close()
 }
}
```

However, I find that GetResponse method now no longer existed in .NET framework 4.5.2. So I find a another one that worked. But it is very slow. So I come up with an idea-using the HTTP head to find out whether it exists, and it is a lot faster:

```csharp
HttpClient httpClient = new HttpClient();

//Getting the Web Response.
 HttpResponseMessage httpResponse = new HttpResponseMessage();
 HttpRequestMessage request = new HttpRequestMessage(HttpMethod.Get, new Uri(website));
 httpResponse = await httpClient.SendRequestAsync(request);
 if(httpResponse.ToString().Contains(“StatusCode: 404”)){
 // do something when the page doesn’t exist
 }
```

![real working code](https://cdn.patrickwu.space/posts/dev/cs+detect-webpage-exist.png)