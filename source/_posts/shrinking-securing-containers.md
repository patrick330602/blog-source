---
title: Shrinking and Securing that Container
tags:
  - Kubernetes
  - DevOps
  - Container
  - Security
date: 2022-08-14 14:29:50
---


## TL; DR

This article will talk about several tools I am recently using to better secure the existing and in-development projects that use Kubernetes.

## it all starts with scanning...

As you might know, I recently joined a new company and switched my main focus to security management and improvement (Also some software development). Since different client uses different scanning tools (Like Plasma Cloud or Synk) for feedback, to save time, I started to find tools to help me get things done easily.

## Finding individual vulnerabilities: `grype`/`syft`

`grype` is an awesome tool to analyze vulnerabilities. It supports a wide range of operating systems and programming languages, but the most important part would be it supports not only the local projects, but also supports Docker/Podman images, SBOM files, and OCI archives. This allows me to scan images without using `docker scan` which requires you to log in to the DockerHub account.

Since `grype` is based on `syft`, this also allowed me to easily check the dependencies' location. For example, I got reports from the Dev team that they cannot find where the vulnerability of a JavaScript dependency `jquery-ui` comes from. Using `grype`/`syft`, I was able to know that the package is located in the python package `matplotlib`.

 `syft` Also could help check dependencies for me, such that I would be able to discover the dependencies that are not intended to be installed to reduce the possibility of getting vulnerability.

You can get them here:
- `grype`: <https://github.com/anchore/grype>
- `syft`: <https://github.com/anchore/syft>

## Check how the image is generated: `dive`

Because all of the projects in the company are delivered in containers, sometimes it got confusing about which package is installed on which step (at which layer) during the build of the images. I used `dive` to help me with this part. `dive` allows you to peek through the images' layers, and check which files and folders are being added, deleted, and modified.

Using this, I can provide a more minimal installation of containers; I have used this to reduce the layers for the duplicating file-adding-and-deleting process. With fewer dependencies, it also provides less possibility of getting unnecessary vulnerabilities.

You can get it here: <https://github.com/wagoodman/dive>

## Fix the vulnerability from the source: Snyk for VSCode

Sometimes, besides the container images and Dockerfiles, I have to modify the projects to update the images. I then discovered this extension on VSCode called **Snyk for VSCode**. This extension can scan the potentially vulnerable codes from your code, as well as scan the dependencies file and mark the vulnerable dependencies out, for example, `package.json` and `yarn.lock`. 

You can download the extension here:
<https://marketplace.visualstudio.com/items?itemName=snyk-security.snyk-vulnerability-scanner>

## Final words

These are just what I use for solving my own problem. There are many more solutions on the internet that are possibly better than them. I just hope these tools will also help you with certain tasks.