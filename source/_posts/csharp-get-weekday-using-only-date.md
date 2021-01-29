---
title: 'Get WeekDay Using Pure Date in C#'
date: 2015-04-16 21:47:16
tags:
- C#
---
Recently, I need to get a weekday purely based on date. Luckily, I find a mathematical way to solve this problem. In this example, it will load the weekday in the `TextBox1`.<!--more-->
```
protected void Page_Load(object sender, EventArgs e)
{
    int m = System.DateTime.Today.Month;
    int y = System.DateTime.Today.Year;
    int d = System.DateTime.Today.Day;
    int weeks = getWeekDay(y, m, d);
    switch (weeks)
    {
        case 1:
            this.TextBox1.Text = "Monday";
            break;
        case 2:
            this.TextBox1.Text = "Tuesday";
            break;
        case 3:
            this.TextBox1.Text = "Wednesday";
            break;
        case 4:
            this.TextBox1.Text = "Thursday";
            break;
        case 5:
            this.TextBox1.Text = "Friday";
            break;
        case 6:
            this.TextBox1.Text = "Saturday";
            break;
        case 7:
            this.TextBox1.Text = "Sunday";
            break;
    }   
}

 

    /// <summary>Get WeekDay Using Date</summary>
    /// <param name="y">Year</param>
    /// <param name="m">Month</param>
    /// <param name="d">Day</param>
    /// <returns>WeekDay</returns>
    public static int getWeekDay(int y, int m, int d)
    {
        if (m == 1) m = 13;
        if (m == 2) m = 14;
        int week = (d + 2 * m + 3 * (m + 1) / 5 + y + y / 4 - y / 100 + y / 400) % 7 + 1;
        return week;
    }
```