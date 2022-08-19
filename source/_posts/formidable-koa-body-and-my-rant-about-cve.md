---
title: formidable, koa-body and my rant about CVE
tags:
  - DevOps
  - Container
  - Security
  - CVE
  - Vulnerability
  - NodeJS
  - TypeScript
date: 2022-08-19 09:39:27
---


## TL; DR

This is a recent thing that happened to me around a vulnerability that caused incompatibility in the project in my current company, and my thought (rant) on CVEs.

## CVE-2022-29622

[CVE-2022-29622](https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2022-29622). This is one of the vulnerabilities reported by the customer after rounds of sending fixed versions. After my initial investigation, this is a vulnerability that affects a nodeJS package called `formidable`. A quick `yarn why` revealed that this is used by `koa-body`, a package that our project directly used. It is quickly revealed that `formidable` had patched the issue in version  `3.2.4`. Then it's easy, just a simple `resolution` right? Oh boy, I was wrong...

## ERR_REQUIRE_ESM

I was enjoying my weekend when the Server team pinged me and told me the images I delivered cannot be started on Kubernetes and they are tossing errors:

```
Error [ERR_REQUIRE_ESM]: Must use import to load ES Module: ./node_modules/formidable/src/index.js
require() of ES modules is not supported.
```

Well well well. It's an ES Module. We now got a problem. According to the [website](https://github.com/node-formidable/formidable/blob/master/VERSION_NOTES.md), `formidable@v3` is an ES Module without CommonJS support. 

Now we have a very interesting situation:
`formidable@v3` is an ES Module, but it fixed the vulnerability;
`koa-body` is written in CommonJS with TypeScript support; it is not able to use ES Module only packages;
Our project is written in CommonJS.

Oh no.

`koa-body` themselves can [only upgrade to `formidable@v2` at most](https://github.com/koajs/koa-body/issues/200). So, this leaves two options for us:

1. Rewrite `formidable` to backport the fix;
2. Drop `koa-body`.

We end up choosing the latter and delivered the fix.

But I still wrote a fix for option 1 (Although actually not needed, see my rant for more details). If you still have to use `formidable`, you can use a fixed version that patched the problem in `@v2` with a fake 3.2.4 version to bypass the vulnerability: <https://github.com/patrick330602/formidable>.

## The Rant

### Vulnerability

Ahh, vulnerability. 

You know, Debian Security Tracker got [`NOT_FOR_US`](https://security-team.debian.org/security_tracker.html#issues-not-for-us-nfu), Ubuntu Security Tracker got [504](https://twitter.com/callmepkwu/status/1547484325297659904?s=21&t=4owCSw55sgiNKxU10AV_8g), and People got COVID-19.

Software is built by man, people make mistakes, and that means no software is perfect. Softwares are always potentially vulnerable. Heck, even we the human are not vulnerable to the latest vulnerability, COVID-19! 

But there will be people who say no, for example, large cooperation running critical infrastructures. They have strict vulnerability requirements and will toss you a report of 30k vulnerabilities, and tell you to fix it. Yep, that's me. I took a week to generate 500+ suggestions for all dev teams just for that. 

However, this is exactly what I am ranting for. These kinds of unfixable, problematic, or questionable bugs or vulnerabilities are driving me nuts.

### False Positive

From the NVD database, I saw a link to [a Medium article talking about this CVE (And their rants)](https://medium.com/@zsolt.imre/is-cybersecurity-the-next-supply-chain-vulnerability-9a00de745022). This is a really good article talking about the issue, and why this is a false positive vulnerability. 

Problematic vulnerabilities are still really common. They [can be revoked](https://security.snyk.io/vuln/SNYK-JS-FORMIDABLE-2838956) from a third party, but it seems not for CVE themselves. heck, there are even [falsely linked vulnerabilities](https://github.com/anchore/grype/issues/446). However, your (and our) customers usually won't listen. They will point out **it is** a vulnerability and needs to be fixed. What we could only do is tell them they are not able to be fixed now. 

The original [CVE-2022-29622 issue](https://github.com/node-formidable/formidable/issues/856) has some interesting discussions too. This line by `@keymandll`perfectly sums up the problem in CVEs:

> The CVE looks to me like yet another overly eager "security professional" trying to get a CVE attached to his/her name by ignoring context and understanding of responsibilities. Then, all the other lemmings just accept it and pick it up without actually looking into the reported "issue".

The funny thing about that Medium article is that it says, and I quote:

> Even the National Vulnerability Database (NVD) is willing to accept anything nowadays?

Well, guess where I find the article? The CVE site!

Oh the irony.