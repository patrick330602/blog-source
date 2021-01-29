---
title: Color With C#
date: 2016/6/12 08:00:00
tags:
- C#
---
  Recently, i am doing a research on a project to convert RGB to HSB, which leads me to an awesome [article](http://www.codeproject.com/Articles/19045/Manipulating-colors-in-NET-Part-1) on CodeProject from 9 years ago. Hence, I sort the article a little bit to help myself understand more of color agorithm.


### Index
[What is "Color"?](#def)

[Color models](#cm)

- [RGB](#rgb)
- [HSB](#hsb) 
- [HSL](#hsl) 
- [CMYK](#cmyk) 
- [YUV](#yuv)
- [Cie XYZ](#xyz) 
- [Cie La*b*](#lab) 

[Conversion between models](#cbm)

+ [RGB to ...](#rgb2) 
  - [a - RGB to HSB](#rgb2hsb)
  - [b - RGB to HSL](#rgb2hsl)
  - [c - RGB to CMYK](#rgb2cmyk)
  - [d - RGB to YUV (YUV444)](#rgb2yuv)
  - [e - RGB to web color](#rgb2wc)
  - [f - RGB to XYZ](#rgb2xyz)
  - [g - RGB to La*b*](#rgb2lab)
+ [HSB to...](#hsb2)
  - [a - HSB to RGB](#hsb2rgb)
  - [b - HSB to HSL](#hsb2hsl)
  - [c - HSB to CMYK](#hsb2cmyk)
  - [d - HSB to YUV](#hsb2yuv)
+ [HSL to...](#hsl2)
  - [a - HSL to RGB](#hsl2rgb)
  - [b - HSL to HSB](#hsl2hsb)
  - [c - HSL to CMYK](#hsl2cmyk)
  - [d - HSL to YUV](#hsl2yuv)
+ [CMYK to...](#cmyk2)
  - [a - CMYK to RGB](#cmyk2rgb)
  - [b - CMYK to HSB](#cmyk2hsb)
  - [c - CMYK to HSL](#cmyk2hsl)
  - [d - CMYK to YUV](#cmyk2yuv)
+ [YUV to...](#yuv2)
  - [a - YUV to RGB](#yuv2rgb)
  - [b - YUV to HSB](#yuv2hsb)
  - [c - YUV to HSL](#yuv2hsl)
  - [d - YUV to YUV](#yuv2yuv)
+ [Cie XYZ to...](#xyz2)
  - [a - XYZ to RGB](#xyz2rgb)
  - [b - XYZ to La*b*](#xyz2lab)
+ [Cie La*b* to...](#lab2)
  - [a - La*b* to XYZ](#lab2xyz)
  - [b - La*b* to RGB](#lab2rgb)

[Using the code](#utc) 

### What is "Color"?
<a name="def" id="def" ></a>"**Color** is the visual perceptual property corresponding in humans to the categories called red, yellow, white, etc. Color derives from the spectrum of light (distribution of light energy versus wavelength) interacting in the eye with the spectral sensitivities of the light receptors. Color categories and physical specifications of color are also associated with objects, materials, light sources, etc., based on their physical properties such as light absorption, reflection, or emission spectra.

[Color definition, Wikipedia.](http://en.wikipedia.org/wiki/Color)

**Colorimetry** is the science that describes colors in numbers, or provides a physical color match using a variety of measurement instruments. Colorimetry is used in chemistry, and in industries such as color printing, textile manufacturing, paint manufacturing and in the food industry.

[Colorimetry definition, Wikipedia.](http://en.wikipedia.org/wiki/Colorimetry)

Then, how can we display colors as numbers? the answer: color models.

### Color Models

<a name="cm" id="cm" ></a>

#### RGB (Red Green Blue)

<a name="rgb" id="rgb" ></a>The **RGB** (**R**ed, **G**reen, **B**lue) color model is the most known, and the most used every day. It defines a color space in terms of three components: 

- **R**ed, which ranges from 0-255 
- **G**reen, which ranges from 0-255 
- **B**lue, which ranges from 0-255 

The **RGB** color model is an additive one. In other words, **R**ed, **G**reen and **B**lue values (known as the three primary colors) are combined to reproduce other colors.

For example, the color "Red" can be represented as [R=255, G=0, B=0], "Violet" as [R=238, G=130, B=238], etc.

Its common graphic representation is the following image:

![RGB](https://cdn.patrickwu.space/posts/dev/color/rgb.png) 

In .NET, the Color structure use this model to provide color support through R, G and B properties. 

```csharp
Console.WriteLine(String.Format("R={0}, G={1}, B={2}",
    Color.Red.R, Color.Red.G, Color.Red.B);
Console.WriteLine(String.Format("R={0}, G={1}, B={2}",
    Color.Cyan.R, Color.Cyan.G, Color.Cyan.B);
Console.WriteLine(String.Format("R={0}, G={1}, B={2}",
    Color.White.R, Color.White.G, Color.White.B);
Console.WriteLine(String.Format("R={0}, G={1}, B={2}",
    Color.SteelBlue.R, Color.SteelBlue.G, Color.SteelBlue.B);

// etc...
```

but this is not its only usage. For this reason, we can define a dedicated RGB structure for further coding, as shown below:

```csharp
/// <summary>
/// RGB structure.
/// </summary>
public struct RGB
{
    /// <summary>
    /// Gets an empty RGB structure;
    /// </summary>
    public static readonly RGB Empty = new RGB();
    
    private int red;
    private int green;
    private int blue;
    
    public static bool operator ==(RGB item1, RGB item2)
    {
        return (
            item1.Red == item2.Red
            && item1.Green == item2.Green
            && item1.Blue == item2.Blue
            );
    }
    
    public static bool operator !=(RGB item1, RGB item2)
    {
        return (
            item1.Red != item2.Red
            || item1.Green != item2.Green
            || item1.Blue != item2.Blue
            );
    }
    
    /// <summary>
    /// Gets or sets red value.
    /// </summary>
    public int Red
    {
        get
        {
            return red;
        }
        set
        {
                red = (value>255)? 255 : ((value<0)?0 : value);
        }
    }
    
    /// <summary>
    /// Gets or sets red value.
    /// </summary>
    public int Green
    {
        get
        {
            return green;
        }
        set
        {
            green = (value>255)? 255 : ((value<0)?0 : value);
        }
    }
    
    /// <summary>
    /// Gets or sets red value.
    /// </summary>
    public int Blue
    {
        get
        {
            return blue;
        }
        set
        {
            blue = (value>255)? 255 : ((value<0)?0 : value);
        }
    }
    
    public RGB(int R, int G, int B)
    {
        this.red = (R>255)? 255 : ((R<0)?0 : R);
        this.green = (G>255)? 255 : ((G<0)?0 : G);
        this.blue = (B>255)? 255 : ((B<0)?0 : B);
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (RGB)obj);
    }
    
    public override int GetHashCode()
    {
        return Red.GetHashCode() ^ Green.GetHashCode() ^ Blue.GetHashCode();
    }
}
```
#### HSB color space

<a name="hsb" id="hsb" ></a>The **HSB** (**H**ue, **S**aturation, **B**rightness) color model defines a color space in terms of three constituent components: 

+ **H**ue : the color type (such as red, blue, or yellow).
  - Ranges from 0 to 360° in most applications. (each value corresponds to one color : 0 is red, 45 is a shade of orange and 55 is a shade of yellow). 

+ **S**aturation : the intensity of the color.
  - Ranges from 0 to 100% (0 means no color, that is a shade of grey between black and white; 100 means intense color). 
  - Also sometimes called the "purity" by analogy to the **colorimetric** quantities excitation purity. 

+ **B**rightness (or **V**alue) : the brightness of the color.
  - Ranges from 0 to 100% (0 is always black; depending on the saturation, 100 may be white or a more or less saturated color). 


Its common graphic representation is the following image:

![HSB Cone](https://cdn.patrickwu.space/posts/dev/color/hsv.png) 

 The **HSB** model is also known as **HSV** (**H**ue, **S**aturation, **V**alue) model. The **HSV** model was created in 1978 by [Alvy Ray Smith](http://en.wikipedia.org/wiki/Alvy_Ray_Smith). It is a nonlinear transformation of the RGB color space. In other words, color is not defined as a simple combination (addition/substraction) of primary colors but as a mathematical transformation. 

**<u>Note:</u>** **HSV** and **HSB** are the same, but **HSL** is different.

All this said, a HSB structure can be :

```csharp
/// <summary>
/// Structure to define HSB.
/// </summary>
public struct HSB
{
    /// <summary>
    /// Gets an empty HSB structure;
    /// </summary>
    public static readonly HSB Empty = new HSB();
    
    private double hue;
    private double saturation;
    private double brightness;
    
    public static bool operator ==(HSB item1, HSB item2)
    {
        return (
            item1.Hue == item2.Hue
            && item1.Saturation == item2.Saturation
            && item1.Brightness == item2.Brightness
            );
    }
    
    public static bool operator !=(HSB item1, HSB item2)
    {
        return (
            item1.Hue != item2.Hue
            || item1.Saturation != item2.Saturation
            || item1.Brightness != item2.Brightness
            );
    }
    
    /// <summary>
    /// Gets or sets the hue component.
    /// </summary>
    public double Hue
    {
        get
        {
            return hue;
        }
        set
        {
            hue = (value>360)? 360 : ((value<0)?0:value);
        }
    }
    
    /// <summary>
    /// Gets or sets saturation component.
    /// </summary>
    public double Saturation
    {
        get
        {
            return saturation;
        }
        set
        {
            saturation = (value>1)? 1 : ((value<0)?0:value);
        }
    }
    
    /// <summary>
    /// Gets or sets the brightness component.
    /// </summary>
    public double Brightness
    {
        get
        {
            return brightness;
        }
        set
        {
            brightness = (value>1)? 1 : ((value<0)? 0 : value);
        }
    }
    
    /// <summary>
    /// Creates an instance of a HSB structure.
    /// </summary>
    /// <param name="h">Hue value.</param>
    /// <param name="s">Saturation value.</param>
    /// <param name="b">Brightness value.</param>
    public HSB(double h, double s, double b)
    {
        hue = (h>360)? 360 : ((h<0)?0:h);
        saturation = (s>1)? 1 : ((s<0)?0:s);
        brightness = (b>1)? 1 : ((b<0)?0:b);
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (HSB)obj);
    }
    
    public override int GetHashCode()
    {
        return Hue.GetHashCode() ^ Saturation.GetHashCode() ^
            Brightness.GetHashCode();
    }
}
```

#### HSL color space

<a name="hsl" id="hsl"></a> The **HSL** color space, also called **HLS** or **HSI**, stands for: 

+ **H**ue : the color type (such as red, blue, or yellow). 
  - Ranges from 0 to 360° in most applications (each value corresponds to one color : 0 is red, 45 is a shade of orange and 55 is a shade of yellow). 
+ **S**aturation : variation of the color depending on the lightness. 
  - Ranges from 0 to 100% (from the center of the black&white axis). 
+ **L**ightness (also Luminance or Luminosity or Intensity). 
  - Ranges from 0 to 100% (from black to white). Its common graphic representation is the following image:

![The HSL cone; Wikipedia image.](https://cdn.patrickwu.space/posts/dev/color/hsl.png)

**HSL** is similar to **HSB**. The main difference is that **HSL** is symmetrical to lightness and darkness. This means that: 

- In **HSL**, the Saturation component always goes from fully saturated color to the equivalent gray (in **HSB**, with B at maximum, it goes from saturated color to white). 
- In **HSL**, the Lightness always spans the entire range from black through the chosen hue to white (in **HSB**, the B component only goes half that way, from black to the chosen hue).

 For my part, **HSL** offers a more accurate (even if it's not absolute) color approximation than **HSB**. All this said, a **HSL** structure can be:

```csharp
 /// <summary>
/// Structure to define HSL.
/// </summary>
public struct HSL
{
    /// <summary>
    /// Gets an empty HSL structure;
    /// </summary>
    public static readonly HSL Empty = new HSL();
    
    private double hue;
    private double saturation;
    private double luminance;
    
    public static bool operator ==(HSL item1, HSL item2)
    {
        return (
            item1.Hue == item2.Hue
            && item1.Saturation == item2.Saturation
            && item1.Luminance == item2.Luminance
            );
    }
    
    public static bool operator !=(HSL item1, HSL item2)
    {
        return (
            item1.Hue != item2.Hue
            || item1.Saturation != item2.Saturation
            || item1.Luminance != item2.Luminance
            );
    }
    
    /// <summary>
    /// Gets or sets the hue component.
    /// </summary>
    public double Hue
    {
        get
        {
            return hue;
        }
        set
        {
            hue = (value>360)? 360 : ((value<0)?0:value);
        }
    }
    
    /// <summary>
    /// Gets or sets saturation component.
    /// </summary>
    public double Saturation
    {
        get
        {
            return saturation;
        }
        set
        {
            saturation = (value>1)? 1 : ((value<0)?0:value);
        }
    }
    
    /// <summary>
    /// Gets or sets the luminance component.
    /// </summary>
    public double Luminance
    {
        get
        {
            return luminance;
        }
        set
        {
            luminance = (value>1)? 1 : ((value<0)? 0 : value);
        }
    }
    
    /// <summary>
    /// Creates an instance of a HSL structure.
    /// </summary>
    /// <param name="h">Hue value.</param>
    /// <param name="s">Saturation value.</param>
    /// <param name="l">Lightness value.</param>
    public HSL(double h, double s, double l)
    {
        this.hue = (h>360)? 360 : ((h<0)?0:h);
        this.saturation = (s>1)? 1 : ((s<0)?0:s);
        this.luminance = (l>1)? 1 : ((l<0)?0:l);
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (HSL)obj);
    }
    
    public override int GetHashCode()
    {
        return Hue.GetHashCode() ^ Saturation.GetHashCode() ^
            Luminance.GetHashCode();
    }
}
```

#### CMYK color space

<a name="cmyk" id=“cmyk”></a>The **CMYK** color space, also known as **CMJN**, stands for:
+ **C**yan.
  - Ranges from 0 to 100% in most applications. 
+ **M**agenta. 
  -  Ranges from 0 to 100% in most applications. 
+ **Y**ellow. 
  -   Ranges from 0 to 100% in most applications. 
+ blac**K**. 
  -    Ranges from 0 to 100% in most applications.

It is a subtractive color model used in color printing.**CMYK** works on an optical illusion that is based on light absorption.

The principle is to superimpose three images; one for cyan, one for magenta and one for yellow; which will reproduce colors. Its common graphic representation is the following image:

![CMYK](https://cdn.patrickwu.space/posts/dev/color/cmyk.png)

Like the **RGB** color model, **CMYK** is a combination of primary colors (cyan, magenta, yellow and black). It is, probably, the only thing they have in common.

**CMYK** suffers from a lack of color shades that causes holes in the color spectrum it can reproduce. That's why there are often differencies when someone convert a color between **CMYK** to **RGB**.

Why using this model? Why black is used? you can tell me... Well it's only for practical purpose. Wikipedia said: 
- To improve print quality and reduce moiré patterns, 
- Text is typically printed in black and includes fine detail (such as serifs); so to reproduce text - using three inks would require an extremely precise alignment for each three components image.
- A combination of cyan, magenta, and yellow pigments don't produce (or rarely) pure black. 
  Mixing all three color inks together to make black can make the paper rather wet when not using dry toner, which is an issue in high speed printing where the paper must dry extremely rapidly to avoid marking the next sheet, and poor quality paper such as newsprint may break if it becomes too wet.
- Using a unit amount of black ink rather than three unit amounts of the process color inks can lead to significant cost savings (black ink is often cheaper).

Let's come back to our reality. A **CMYK** structure can be:

```csharp
/// <summary>
/// Structure to define CMYK.
/// </summary>
public struct CMYK
{
    /// <summary>
    /// Gets an empty CMYK structure;
    /// </summary>
    public readonly static CMYK Empty = new CMYK();
    
    private double c;
    private double m;
    private double y;
    private double k;
    
    public static bool operator ==(CMYK item1, CMYK item2)
    {
        return (
            item1.Cyan == item2.Cyan
            && item1.Magenta == item2.Magenta
            && item1.Yellow == item2.Yellow
            && item1.Black == item2.Black
            );
    }
    
    public static bool operator !=(CMYK item1, CMYK item2)
    {
        return (
            item1.Cyan != item2.Cyan
            || item1.Magenta != item2.Magenta
            || item1.Yellow != item2.Yellow
            || item1.Black != item2.Black
            );
    }
    
    public double Cyan
    {
        get
        {
            return c;
        }
        set
        {
            c = value;
            c = (c>1)? 1 : ((c<0)? 0 : c);
        }
    }
    
    public double Magenta
    {
        get
        {
            return m;
        }
        set
        {
            m = value;
            m = (m>1)? 1 : ((m<0)? 0 : m);
        }
    }
    
    public double Yellow
    {
        get
        {
            return y;
        }
        set
        {
            y = value;
            y = (y>1)? 1 : ((y<0)? 0 : y);
        }
    }
    
    public double Black
    {
        get
        {
            return k;
        }
        set
        {
            k = value;
            k = (k>1)? 1 : ((k<0)? 0 : k);
        }
    }
    
    /// <summary>
    /// Creates an instance of a CMYK structure.
    /// </summary>
    public CMYK(double c, double m, double y, double k)
    {
        this.c = c;
        this.m = m;
        this.y = y;
        this.k = k;
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (CMYK)obj);
    }
    
    public override int GetHashCode()
    {
        return Cyan.GetHashCode() ^
          Magenta.GetHashCode() ^ Yellow.GetHashCode() ^ Black.GetHashCode();
    }

}
```

#### YUV color space

<a name="yuv" id="yuv"></a>The **YUV** model defines a color space in terms of one luma and two chrominance components. The **YUV** color model is used in the PAL, NTSC, and SECAM composite color video standards.

**YUV** models human perception of color more closely than the standard RGB model used in computer graphics hardware.

The **YUV** color space stands for:
+ **Y**, the luma component, or the brightness. 
  - Ranges from 0 to 100% in most applications.
+ **U** and **V** are the chrominance components (blue-luminance and red-luminance differences components).
  - Expressed as factors depending on the **YUV** version you want to use.

A graphic representation is the following image:

![U-V color plane, Y value = 0.5; Wikipedia image](https://cdn.patrickwu.space/posts/dev/color/yuv.png)

A **YUV** structure can be:

```csharp
/// <summary>
/// Structure to define YUV.
/// </summary>
public struct YUV
{
    /// <summary>
    /// Gets an empty YUV structure.
    /// </summary>
    public static readonly YUV Empty = new YUV();
    
    private double y;
    private double u;
    private double v;
    
    public static bool operator ==(YUV item1, YUV item2)
    {
        return (
            item1.Y == item2.Y
            && item1.U == item2.U
            && item1.V == item2.V
            );
    }
    
    public static bool operator !=(YUV item1, YUV item2)
    {
        return (
            item1.Y != item2.Y
            || item1.U != item2.U
            || item1.V != item2.V
            );
    }
    
    public double Y
    {
        get
        {
            return y;
        }
        set
        {
            y = value;
            y = (y>1)? 1 : ((y<0)? 0 : y);
        }
    }
    
    public double U
    {
        get
        {
            return u;
        }
        set
        {
            u = value;
            u = (u>0.436)? 0.436 : ((u<-0.436)? -0.436 : u);
        }
    }
    
    public double V
    {
        get
        {
            return v;
        }
        set
        {
            v = value;
            v = (v>0.615)? 0.615 : ((v<-0.615)? -0.615 : v);
        }
    }
    
    /// <summary>
    /// Creates an instance of a YUV structure.
    /// </summary>
    public YUV(double y, double u, double v)
    {
        this.y = (y>1)? 1 : ((y<0)? 0 : y);
        this.u = (u>0.436)? 0.436 : ((u<-0.436)? -0.436 : u);
        this.v = (v>0.615)? 0.615 : ((v<-0.615)? -0.615 : v);
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (YUV)obj);
    }
    
    public override int GetHashCode()
    {
        return Y.GetHashCode() ^ U.GetHashCode() ^ V.GetHashCode();
    }

}
```
#### CIE XYZ color space

<a name="xyz" id="xyz"></a>In opposition to the previous models, the **CIE XYZ** model defines an absolute color space. It is also known as the **CIE 1931 XYZ** color space and stands for:
+ **X**, which can be compared to red
  - Ranges from 0 to 0.9505
+ **Y**, which can be compared to green
  - Ranges from 0 to 1.0
+ **Z**, which can be compared to blue
  - Ranges from 0 to 1.089

Before trying to explain why I include this color space in this article, you have to know that it's one of the first standards created by the International Commission on Illumination (CIE) in 1931. It is based on direct measurements of the human eye, and serves as the basis from which many other color spaces are defined.

A graphic representation is the following image:

![CIE XYZ Color Space](https://cdn.patrickwu.space/posts/dev/color/xyz.png)

A **CIE XYZ** structure can be:

```csharp
/// <summary>
/// Structure to define CIE XYZ.
/// </summary>
public struct CIEXYZ
{
    /// <summary>
    /// Gets an empty CIEXYZ structure.
    /// </summary>
    public static readonly CIEXYZ Empty = new CIEXYZ();
    /// <summary>
    /// Gets the CIE D65 (white) structure.
    /// </summary>
    public static readonly CIEXYZ D65 = new CIEXYZ(0.9505, 1.0, 1.0890);


    private double x;
    private double y;
    private double z;
    
    public static bool operator ==(CIEXYZ item1, CIEXYZ item2)
    {
        return (
            item1.X == item2.X
            && item1.Y == item2.Y
            && item1.Z == item2.Z
            );
    }
    
    public static bool operator !=(CIEXYZ item1, CIEXYZ item2)
    {
        return (
            item1.X != item2.X
            || item1.Y != item2.Y
            || item1.Z != item2.Z
            );
    }
    
    /// <summary>
    /// Gets or sets X component.
    /// </summary>
    public double X
    {
        get
        {
            return this.x;
        }
        set
        {
            this.x = (value>0.9505)? 0.9505 : ((value<0)? 0 : value);
        }
    }
    
    /// <summary>
    /// Gets or sets Y component.
    /// </summary>
    public double Y
    {
        get
        {
            return this.y;
        }
        set
        {
            this.y = (value>1.0)? 1.0 : ((value<0)?0 : value);
        }
    }
    
    /// <summary>
    /// Gets or sets Z component.
    /// </summary>
    public double Z
    {
        get
        {
            return this.z;
        }
        set
        {
            this.z = (value>1.089)? 1.089 : ((value<0)? 0 : value);
        }
    }
    
    public CIEXYZ(double x, double y, double z)
    {
        this.x = (x>0.9505)? 0.9505 : ((x<0)? 0 : x);
        this.y = (y>1.0)? 1.0 : ((y<0)? 0 : y);
        this.z = (z>1.089)? 1.089 : ((z<0)? 0 : z);
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (CIEXYZ)obj);
    }
    
    public override int GetHashCode()
    {
        return X.GetHashCode() ^ Y.GetHashCode() ^ Z.GetHashCode();
    }

}
```
Well! why do I have to include this model?

I have made a quick research to include **Cie L\*a*b*** color model in this article, and I find that a conversion to an absolute color space is required before converting to **L\*a*b***. The model used in the conversion principle is **Cie XYZ**. So, I've included it and now everyone can understand "what are those XYZ values" used further in the article.

#### CIE La*b* color space

<a name="lab" id="lab"></a>A Lab color space is a color-opponent space with dimension L for luminance and a and b for the color-opponent dimensions, based on nonlinearly-compressed **CIE XYZ** color space coordinates."

As said in the previous definition, **CIE La*b*** color space, also know as **CIE 1976** color space, stands for:
- **L\***, the luminance 
- **a\***, the red/green color-opponent dimension 
- **b\*** , the yellow/blue color-opponent dimension 

The **La*b*** color model has been created to serve as a device independent model to be used as a reference. It is based directly on the **CIE 1931 XYZ** color space as an attempt to linearize the perceptibility of color differences.

The non-linear relations for L\*, a\*, and b\* are intended to mimic the logarithmic response of the eye, coloring information is referred to the color of the white point of the system.

A **CIE La*b*** structure can be:

```csharp
/// <summary>
/// Structure to define CIE L*a*b*.
/// </summary>
public struct CIELab
{
    /// <summary>
    /// Gets an empty CIELab structure.
    /// </summary>
    public static readonly CIELab Empty = new CIELab();
    
    private double l;
    private double a;
    private double b;


    public static bool operator ==(CIELab item1, CIELab item2)
    {
        return (
            item1.L == item2.L
            && item1.A == item2.A
            && item1.B == item2.B
            );
    }
    
    public static bool operator !=(CIELab item1, CIELab item2)
    {
        return (
            item1.L != item2.L
            || item1.A != item2.A
            || item1.B != item2.B
            );
    }


    /// <summary>
    /// Gets or sets L component.
    /// </summary>
    public double L
    {
        get
        {
            return this.l;
        }
        set
        {
            this.l = value;
        }
    }
    
    /// <summary>
    /// Gets or sets a component.
    /// </summary>
    public double A
    {
        get
        {
            return this.a;
        }
        set
        {
            this.a = value;
        }
    }
    
    /// <summary>
    /// Gets or sets a component.
    /// </summary>
    public double B
    {
        get
        {
            return this.b;
        }
        set
        {
            this.b = value;
        }
    }
    
    public CIELab(double l, double a, double b)
    {
        this.l = l;
        this.a = a;
        this.b = b;
    }
    
    public override bool Equals(Object obj)
    {
        if(obj==null || GetType()!=obj.GetType()) return false;
    
        return (this == (CIELab)obj);
    }
    
    public override int GetHashCode()
    {
        return L.GetHashCode() ^ a.GetHashCode() ^ b.GetHashCode();
    }

}
```

There are still many other formats like [RYB](http://en.wikipedia.org/wiki/RYB_color_model) and [CcMmYK](http://en.wikipedia.org/wiki/CcMmYK_color_model). I still don't intend to create a "color framework"(so do I), but if you have other ideas... 

### Conversion between models

<a name="cbm" id="cbm"></a>

#### A - RGB Conversions

<a name="rgb2" id="rgb2"></a>
Converting **RGB color** to any other model is the basis in conversion algorithms. It implies a normalisation of red, green and blue : value ranges now from [0..255] to [0..1].

##### a - RGB to HSB

<a name="rgb2hsb" id="rgb2hsb"></a>

The conversion principle is the one below:

*H* ? [0, 360]

*S, V, R, G, B* ? [0, 1]

![RGB to HSB](https://cdn.patrickwu.space/posts/dev/color/rgb2-1.png)

![RGB to HSB](https://cdn.patrickwu.space/posts/dev/color/rgb2-2.png)

*V* = *MAX*

Well! Interesting! But what's the C# equivalent? Here it is.

```csharp
/// <summary>
/// Converts RGB to HSB.
/// </summary>
public static HSB RGBtoHSB(int red, int green, int blue)
{
    // normalize red, green and blue values
    double r = ((double)red/255.0);
    double g = ((double)green/255.0);
    double b = ((double)blue/255.0);
    
    // conversion start
    double max = Math.Max(r, Math.Max(g, b));
    double min = Math.Min(r, Math.Min(g, b));
    
    double h = 0.0;
    if(max==r && g>=b)
    {
        h = 60 * (g-b)/(max-min);
    }
    else if(max==r && g < b)
    {
        h = 60 * (g-b)/(max-min) + 360;
    }
    else if(max == g)
    {
        h = 60 * (b-r)/(max-min) + 120;
    }
    else if(max == b)
    {
        h = 60 * (r-g)/(max-min) + 240;
    }
    
    double s = (max == 0)? 0.0 : (1.0 - (min/max));
    
    return new HSB(h, s, (double)max);
}
```

##### b - RGB to HSL

<a name="rgb2hsl" id="rgb2hsl"></a>

The conversion principle is the one below:

*H* ? [0, 360]

*S, L, R, G, B* ? [0, 1]

![RGB to HSL](https://cdn.patrickwu.space/posts/dev/color/rgb2-3.png)
![RGB to HSL](https://cdn.patrickwu.space/posts/dev/color/rgb2-4.png)

*L* = 1/2\*(*MAX* + *MIN*)

The C# equivalent is:

```csharp
/// <summary>
/// Converts RGB to HSL.
/// </summary>
/// <param name="red">Red value, must be in [0,255].</param>
/// <param name="green">Green value, must be in [0,255].</param>
/// <param name="blue">Blue value, must be in [0,255].</param>
public static HSL RGBtoHSL(int red, int green, int blue)
{
    double h=0, s=0, l=0;

    // normalize red, green, blue values
    double r = (double)red/255.0;
    double g = (double)green/255.0;
    double b = (double)blue/255.0;
    
    double max = Math.Max(r, Math.Max(g, b));
    double min = Math.Min(r, Math.Min(g, b));
    
    // hue
    if(max == min)
    {
        h = 0; // undefined
    }
    else if(max==r && g>=b)
    {
        h = 60.0*(g-b)/(max-min);
    }
    else if(max==r && g<b)
    {
        h = 60.0*(g-b)/(max-min) + 360.0;
    }
    else if(max==g)
    {
        h = 60.0*(b-r)/(max-min) + 120.0;
    }
    else if(max==b)
    {
        h = 60.0*(r-g)/(max-min) + 240.0;
    }
    
    // luminance
    l = (max+min)/2.0;
    
    // saturation
    if(l == 0 || max == min)
    {
        s = 0;
    }
    else if(0<l && l<=0.5)
    {
        s = (max-min)/(max+min);
    }
    else if(l>0.5)
    {
        s = (max-min)/(2 - (max+min)); //(max-min > 0)?
    }
    
    return new HSL(
        Double.Parse(String.Format("{0:0.##}", h)),
        Double.Parse(String.Format("{0:0.##}", s)),
        Double.Parse(String.Format("{0:0.##}", l))
        );
}
```

Note: You have probably noticed String.Format in the final line. It's the .NET solution for keeping the same rounding behavior. If you don't understand what I mean, try the sample code below: 

```csharp
Console.WriteLine(Math.Round(4.45, 1)); // returns 4.4.
Console.WriteLine(Math.Round(4.55, 1)); // returns 4.6.
```

You didn't notice a problem? Ok, rouding 4.45 should have returned 4.5 and not 4.4. The solution is using `String.Format()` which always applies "round-to-even" method. 

##### c - RGB to CMYK

<a name="rgb2cmyk" id="rgb2cmyk"></a>

The conversion principle is the one below :

*R, G, B* ? [0, 1]

*t**C'M'Y'* = {1 - *R*, 1 - *G*, 1 - *B*}

*K* = min{*C', M', Y'*}

*t**CMYK* = {0, 0, 0, 1}

 *if K = 1**t**CMYK* = { (*C' - K*)/(1 - *K*), (*M' - K*)/(1 - *K*), (*Y' - K*)/(1 - *K*), *K* } *otherwise*

The C# equivalent is:

```csharp
/// <summary>
/// Converts RGB to CMYK.
/// </summary>
/// <param name="red">Red vaue must be in [0, 255]. </param>
/// <param name="green">Green vaue must be in [0, 255].</param>
/// <param name="blue">Blue vaue must be in [0, 255].</param>
public static CMYK RGBtoCMYK(int red, int green, int blue)
{
    // normalizes red, green, blue values
    double c = (double)(255 - red)/255;
    double m = (double)(255 - green)/255;
    double y = (double)(255 - blue)/255;
    
    double k = (double)Math.Min(c, Math.Min(m, y));
    
    if(k == 1.0)
    {
        return new CMYK(0,0,0,1);
    }
    else
    {
        return new CMYK((c-k)/(1-k), (m-k)/(1-k), (y-k)/(1-k), k);
    }
}
```

##### d - RGB to YUV (YUV444)

<a name="rgb2yuv" id="rgb2yuv"></a>

The conversion principle is the one below :

*R, G, B, Y* ? [0, 1]

*U* ? [-0.436, 0.436]

*V* ? [-0.615, 0.615]

*tYUV* = { (0.299 *R* + 0.587 *G* + 0.114 *B*), (- 0.14713 *R* + 0.28886 *G* + 0.436 *B*), (0.615 *R* + 0.51499 *G* + 0.10001 *B*) }

The C# equivalent is :

```csharp
/// <summary>
/// Converts RGB to YUV.
/// </summary>
/// <param name="red">Red must be in [0, 255].</param>
/// <param name="green">Green must be in [0, 255].</param>
/// <param name="blue">Blue must be in [0, 255].</param>
public static YUV RGBtoYUV(int red, int green, int blue)
{
    YUV yuv = new YUV();

    // normalizes red, green, blue values
    double r = (double)red/255.0;
    double g = (double)green/255.0;
    double b = (double)blue/255.0;
    
    yuv.Y = 0.299*r + 0.587*g + 0.114*b;
    yuv.U = -0.14713*r -0.28886*g + 0.436*b;
    yuv.V = 0.615*r -0.51499*g -0.10001*b;
    
    return yuv;
}
```

##### e - RGB to web color

<a name="rgb2wc" id="rgb2wc"></a>

Haaa! Something I can explain.

As you probably already know, web colors can be defined in two ways: for example, "red" can be defined as `rgb(255,0,0)` or `#FF0000`.

The explanation of the second form is simple : 

- "`#`" character tells that the format is the hexadecimal one. 
- The last 6 characters define 3 pairs: one for "Red", one for "Green" and one for "Blue". 
- Each pair is a hexadecimal value (base 16) of a value which ranges from 0 to 255. 

So, you can divide each color component by 16 and replace numbers superior to 9 by theirs hexadecimal value (eg. 10 = A, 11 = B, etc.)... but the best way is to use `String.Format()` habilities. 

```csharp
/// <summary>
/// Converts a RGB color format to an hexadecimal color.
/// </summary>
/// <param name="r">Red value.</param>
/// <param name="g">Green value.</param>
/// <param name="b">Blue value.</param>
public static string RGBToHex(int r, int g, int b)
{
    return String.Format("#{0:x2}{1:x2}{2:x2}", r, g, b).ToUpper();
}
```

##### f - RGB to XYZ

<a name="rgb2xyz" id="rgb2xyz"></a>

The conversion principle is the one below:

*R, G, B* ? [0, 1]

*a* = 0.055 and *?* ˜ 2.2

![RGB to XYZ](https://cdn.patrickwu.space/posts/dev/color/rgb2-5.png) 

where

![RGB to XYZ](https://cdn.patrickwu.space/posts/dev/color/rgb2-6.png) 

The C# equivalent is :

```csharp
/// <summary>
/// Converts RGB to CIE XYZ (CIE 1931 color space)
/// </summary>
public static CIEXYZ RGBtoXYZ(int red, int green, int blue)
{
    // normalize red, green, blue values
    double rLinear = (double)red/255.0;
    double gLinear = (double)green/255.0;
    double bLinear = (double)blue/255.0;
    
    // convert to a sRGB form
    double r = (rLinear > 0.04045)? Math.Pow((rLinear + 0.055)/(
        1 + 0.055), 2.2) : (rLinear/12.92) ;
    double g = (gLinear > 0.04045)? Math.Pow((gLinear + 0.055)/(
        1 + 0.055), 2.2) : (gLinear/12.92) ;
    double b = (bLinear > 0.04045)? Math.Pow((bLinear + 0.055)/(
        1 + 0.055), 2.2) : (bLinear/12.92) ;
    
    // converts
    return new CIEXYZ(
        (r*0.4124 + g*0.3576 + b*0.1805),
        (r*0.2126 + g*0.7152 + b*0.0722),
        (r*0.0193 + g*0.1192 + b*0.9505)
        );
}
```

##### g - RGB to La*b*

<a name="rgb2lab" id="rgb2lab"></a>

As I said before, converting to the **CIE La*b*** color model is a little bit tricky: we need to convert to **CIE XYZ** before trying to have **La*b*** values.

```csharp
/// <summary>
/// Converts RGB to CIELab.
/// </summary>
public static CIELab RGBtoLab(int red, int green, int blue)
{
    return XYZtoLab( RGBtoXYZ(red, green, blue) );
}
```

The conversion between **XYZ** and **La*b*** is given below.

#### B - HSB conversions

<a name="hsb2" id="hsb2"></a>

##### a - HSB to RGB

<a name="hsb2rgb" id="hsb2rgb"></a>
The conversion principle is the one below :

*H* ? [0, 360]

*S, V, R, G, B* ? [0, 1]

*Hi* = [*H* / 60] mod 6

*f* = (*H* / 60) - *Hi*

*p* = *V* (1 - *S*)

*q* = *V* (1 - *f S*)

*t* = *V* (1 - (1 - *f* ) *S*)

*if Hi* = 0 ? *R* = *V*, *G* = *t*, *B* = *p*
*if Hi* = 1 ? *R* = *q*, *G* = *V*, *B* = *p*
*if Hi* = 2 ? *R* = *p*, *G* = *V*, *B* = *t*
*if Hi* = 3 ? *R* = *p*, *G* = *q*, *B* = *V*
*if Hi* = 4 ? *R* = *t*, *G* = *p*, *B* = *V*
*if Hi* = 5 ? *R* = *V*, *G* = *p*, *B* = *q* 

The C# equivalent? Here it is.

```csharp
/// <summary>
/// Converts HSB to RGB.
/// </summary>
public static RGB HSBtoRGB(double h, double s, double b)
{
    double r = 0;
    double g = 0;
    double b = 0;
    
    if(s == 0)
    {
        r = g = b = b;
    }
    else
    {
        // the color wheel consists of 6 sectors. Figure out which sector
        // you're in.
        double sectorPos = h / 60.0;
        int sectorNumber = (int)(Math.Floor(sectorPos));
        // get the fractional part of the sector
        double fractionalSector = sectorPos - sectorNumber;
    
        // calculate values for the three axes of the color.
        double p = b * (1.0 - s);
        double q = b * (1.0 - (s * fractionalSector));
        double t = b * (1.0 - (s * (1 - fractionalSector)));
    
        // assign the fractional colors to r, g, and b based on the sector
        // the angle is in.
        switch(sectorNumber)
        {
            case 0:
                r = b;
                g = t;
                b = p;
                break;
            case 1:
                r = q;
                g = b;
                b = p;
                break;
            case 2:
                r = p;
                g = b;
                b = t;
                break;
            case 3:
                r = p;
                g = q;
                b = b;
                break;
            case 4:
                r = t;
                g = p;
                b = b;
                break;
            case 5:
                r = b;
                g = p;
                b = q;
                break;
        }
    }
    
    return new RGB(
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}", r*255.0)) ),
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}", g*255.0)) ),
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}", b*255.0)) )
    );
}
```

##### b - HSB to HSL
<a name="hsb2hsl" id="hsb2hsl"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSB**.

```csharp
/// <summary>
/// Converts HSL to HSB.
/// </summary>
public static HSB HSLtoHSB(double h, double s, double l)
{
    RGB rgb = HSLtoRGB(h, s, l);

    return RGBtoHSB(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### c - HSB to CMYK
<a name="hsb2cmyk" id="hsb2cmyk"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **CMYK**.

```csharp
/// <summary>
/// Converts HSB to CMYK.
/// </summary>
public static CMYK HSBtoCMYK(double h, double s, double b)
{
    RGB rgb = HSBtoRGB(h, s, b);

    return RGBtoCMYK(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### d - HSB to YUV
<a name="hsb2yuv" id="hsb2yuv"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **YUV**.

```csharp
/// <summary>
/// Converts HSB to CMYK.
/// </summary>
public static YUV HSBtoYUV(double h, double s, double b)
{
    RGB rgb = HSBtoRGB(h, s, b);

    return RGBtoYUV(rgb.Red, rgb.Green, rgb.Blue);
}
```
#### C - HSL conversions

<a name="hsl2" id="hsl2"></a>

##### a - HSL to RGB

<a name="hsl2rgb" id="hsl2rgb"></a> The conversion principle is the one below :

*H* ? [0, 360]
*S, L, R, G, B* ? [0, 1]

if *L* < 0.5 ? *Q* = *L* × (1 + *S*)
if *L* = 0.5 ? *Q* = *L* + *S* – (*L* × *S*)

*P* = 2 × *L* – *Q*

*Hk* = *H* / 360

*Tr* = *Hk* + 1/3

*Tg* = *Hk*

*Tb* = *Hk* – 1/3

For each c = R,G,B :

if *Tc* < 0 ? *Tc* = *Tc* + 1.0
if *Tc* > 1 ? *Tc* = *Tc* – 1.0

if *Tc* < 1/6 ? *Tc* = *P* + ((*Q* – *P*) × 6.0 × *Tc*)
if 1/6 = *Tc* > 1/2 ? *Tc* = *Q*
if 1/2 = *Tc* > 2/3 ? *Tc* = *P* + ((*Q* – *P*) × (2/3 – *Tc*) × 6.0)
else *Tc* = *P*

The C# equivalent? Here it is.

```csharp
/// <summary>
/// Converts HSL to RGB.
/// </summary>
/// <param name="h">Hue, must be in [0, 360].</param>
/// <param name="s">Saturation, must be in [0, 1].</param>
/// <param name="l">Luminance, must be in [0, 1].</param>
public static RGB HSLtoRGB(double h, double s, double l)
{
    if(s == 0)
    {
        // achromatic color (gray scale)
        return new RGB(
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                l*255.0)) ),
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                l*255.0)) ),
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                l*255.0)) )
            );
    }
    else
    {
        double q = (l<0.5)?(l * (1.0+s)):(l+s - (l*s));
        double p = (2.0 * l) - q;
    
        double Hk = h/360.0;
        double[] T = new double[3];
        T[0] = Hk + (1.0/3.0);    // Tr
        T[1] = Hk;                // Tb
        T[2] = Hk - (1.0/3.0);    // Tg
    
        for(int i=0; i<3; i++)
        {
            if(T[i] < 0) T[i] += 1.0;
            if(T[i] > 1) T[i] -= 1.0;
    
            if((T[i]*6) < 1)
            {
                T[i] = p + ((q-p)*6.0*T[i]);
            }
            else if((T[i]*2.0) < 1) //(1.0/6.0)<=T[i] && T[i]<0.5
            {
                T[i] = q;
            }
            else if((T[i]*3.0) < 2) // 0.5<=T[i] && T[i]<(2.0/3.0)
            {
                T[i] = p + (q-p) * ((2.0/3.0) - T[i]) * 6.0;
            }
            else T[i] = p;
        }
    
        return new RGB(
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                T[0]*255.0)) ),
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                T[1]*255.0)) ),
            Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
                T[2]*255.0)) )
            );
    }
}
```

##### b - HSL to HSB

<a name="hsl2hsb" id="hsl2hsb"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSB**.

```csharp
/// <summary>
/// Converts HSL to HSB.
/// </summary>
public static HSB HSLtoHSB(double h, double s, double l)
{
    RGB rgb = HSLtoRGB(h, s, l);

    return RGBtoHSB(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### c - HSL to CMYK

<a name="hsl2cymk" id="hsl2cymk"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **CMYK**.

```csharp
/// <summary>
/// Converts HSL to CMYK.
/// </summary>
public static CMYK HSLtoCMYK(double h, double s, double l)
{
    RGB rgb = HSLtoRGB(h, s, l);

    return RGBtoCMYK(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### d - HSL to YUV

<a name="hsl2yuv" id="hsl2yuv"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **YUV**.

```csharp
/// <summary>
/// Converts HSL to YUV.
/// </summary>
public static YUV HSLtoYUV(double h, double s, double l)
{
    RGB rgb = HSLtoRGB(h, s, l);

    return RGBtoYUV(rgb.Red, rgb.Green, rgb.Blue);
}
```

#### D - CMYK conversions

<a name="cmyk2" id="cmyk2"></a>
##### a - CMYK to RGB

<a name="cmyk2rgb" id="cmyk2rgb"></a> The conversion principle is the one below :

*tRGB* = { (1 - *C*) × (1 - *K*) , (1 - *M*) × (1 - *K*), (1 - *Y*) × (1 - *K*)}

The C# equivalent? Here it is.

```csharp
/// <summary>
/// Converts CMYK to RGB.
/// </summary>
public static Color CMYKtoRGB(double c, double m, double y, double k)
{
    int red = Convert.ToInt32((1-c) * (1-k) * 255.0);
    int green = Convert.ToInt32((1-m) * (1-k) * 255.0);
    int blue = Convert.ToInt32((1-y) * (1-k) * 255.0);
    
    return Color.FromArgb(red, green, blue);
}
```

##### b - CMYK to HSL

<a name="cmyk2hsl" id="cmyk2hsl"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSL**.

```csharp
/// <summary>
/// Converts CMYK to HSL.
/// </summary>
public static HSL CMYKtoHSL(double c, double m, double y, double k)
{
    RGB rgb = CMYKtoRGB(c, m, y, k);

    return RGBtoHSL(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### c - CMYK to HSB

<a name="cmyk2hsb" id="cmyk2hsb"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSB**.

```csharp
/// <summary>
/// Converts CMYK to HSB.
/// </summary>
public static HSB CMYKtoHSB(double c, double m, double y, double k)
{
    RGB rgb = CMYKtoRGB(c, m, y, k);

    return RGBtoHSB(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### d - CMYK to YUV

<a name="cmyk2yuv" id="cmyk2yuv"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **YUV**.

```csharp
/// <summary>
/// Converts CMYK to YUV.
/// </summary>
public static YUV CMYKtoYUV(double c, double m, double y, double k)
{
    RGB rgb = CMYKtoRGB(c, m, y, k);

    return RGBtoYUV(rgb.Red, rgb.Green, rgb.Blue);
}
```

#### E - YUV conversions

<a name="yuv2" id="yuv2"></a>

##### a - YUV to RGB

<a name="yuv2rgb" id="yuv2rgb"></a> The conversion principle is the one below :

*R, G, B, Y* ? [0, 1]

*U* ? [-0.436, 0.436]

*V* ? [-0.615, 0.615]

*tRGB* = { (Y + 1.13983 *V*), (*Y* - 0.39466 *U* - 0.58060 *V*), (*Y* + 2.03211 *U*) }

The C# equivalent? Here it is.

```csharp
/// <summary>
/// Converts YUV to RGB.
/// </summary>
/// <param name="y">Y must be in [0, 1].</param>
/// <param name="u">U must be in [-0.436, +0.436].</param>
/// <param name="v">V must be in [-0.615, +0.615].</param>
public static RGB YUVtoRGB(double y, double u, double v)
{
    RGB rgb = new RGB();

    rgb.Red = Convert.ToInt32((y + 1.139837398373983740*v)*255);
    rgb.Green = Convert.ToInt32((
        y - 0.3946517043589703515*u - 0.5805986066674976801*v)*255);
    rgb.Blue = Convert.ToInt32((y + 2.032110091743119266*u)*255);
    
    return rgb;
}
```

##### b - YUV to HSL

<a name="yuv2hsl" id="yuv2hsl"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSL**.

```csharp
/// <summary>
/// Converts YUV to HSL.
/// </summary>
/// <param name="y">Y must be in [0, 1].</param>
/// <param name="u">U must be in [-0.436, +0.436].</param>
/// <param name="v">V must be in [-0.615, +0.615].</param>
public static HSL YUVtoHSL(double y, double u, double v)
{
    RGB rgb = YUVtoRGB(y, u, v);

    return RGBtoHSL(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### c - YUV to HSB

<a name="yuv2hsb" id="yuv2hsb"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **HSB**.

```csharp
/// <summary>
/// Converts YUV to HSB.
/// </summary>
/// <param name="y">Y must be in [0, 1].</param>
/// <param name="u">U must be in [-0.436, +0.436].</param>
/// <param name="v">V must be in [-0.615, +0.615].</param>
public static HSB YUVtoHSB(double y, double u, double v)
{
    RGB rgb = YUVtoRGB(y, u, v);

    return RGBtoHSB(rgb.Red, rgb.Green, rgb.Blue);
}
```

##### d - YUV to CMYK

<a name="yuv2cmyk" id="yuv2cmyk"></a>
Nothing new: conversion principle is to convert to **RGB** and then to **CMYK**.

```csharp
/// <summary>
/// Converts YUV to CMYK.
/// </summary>
/// <param name="y">Y must be in [0, 1].</param>
/// <param name="u">U must be in [-0.436, +0.436].</param>
/// <param name="v">V must be in [-0.615, +0.615].</param>
public static CMYK YUVtoCMYK(double y, double u, double v)
{
    RGB rgb = YUVtoRGB(y, u, v);

    return RGBtoCMYK(rgb.Red, rgb.Green, rgb.Blue);
}
```
#### F - XYZ conversions

<a name="xyz2" id="xyz2"></a>

##### a - XYZ to RGB

<a name="xyz2rgb" id="xyz2rgb"></a>The conversion principle is the one below:


*a* = 0.055
![XYZ to RGB](https://cdn.patrickwu.space/posts/dev/color/xyz2-1.png)
then
![XYZ to RGB](https://cdn.patrickwu.space/posts/dev/color/xyz2-2.png)

The C# equivalent is :

```csharp
/// <summary>
/// Converts CIEXYZ to RGB structure.
/// </summary>
public static RGB XYZtoRGB(double x, double y, double z)
{
    double[] Clinear = new double[3];
    Clinear[0] = x*3.2410 - y*1.5374 - z*0.4986; // red
    Clinear[1] = -x*0.9692 + y*1.8760 - z*0.0416; // green
    Clinear[2] = x*0.0556 - y*0.2040 + z*1.0570; // blue
    
    for(int i=0; i<3; i++)
    {
        Clinear[i] = (Clinear[i]<=0.0031308)? 12.92*Clinear[i] : (
            1+0.055)* Math.Pow(Clinear[i], (1.0/2.4)) - 0.055;
    }
    
    return new RGB(
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
            Clinear[0]*255.0)) ),
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
            Clinear[1]*255.0)) ),
        Convert.ToInt32( Double.Parse(String.Format("{0:0.00}",
            Clinear[2]*255.0)) )
        );
}		                          	 	    
```

##### b - XYZ to La*b*

<a name="xyz2lab" id="xyz2lab"></a>The conversion principle is the one below:

![XYZ to La*b*](https://cdn.patrickwu.space/posts/dev/color/xyz2-3.png)
![XYZ to La*b*](https://cdn.patrickwu.space/posts/dev/color/xyz2-4.png)
![XYZ to La*b*](https://cdn.patrickwu.space/posts/dev/color/xyz2-5.png)

where
![XYZ to La*b*](https://cdn.patrickwu.space/posts/dev/color/xyz2-6.png) for *t* > 0.008856
![XYZ to La*b*](https://cdn.patrickwu.space/posts/dev/color/xyz2-7.png) otherwise

Xn, Yn and Zn are the CIE XYZ tristimulus values of the reference white point.

The C# equivalent is:

```csharp
/// <summary>
/// XYZ to L*a*b* transformation function.
/// </summary>
private static double Fxyz(double t)
{
    return ((t > 0.008856)? Math.Pow(t, (1.0/3.0)) : (7.787*t + 16.0/116.0));
}

/// <summary>
/// Converts CIEXYZ to CIELab.
/// </summary>
public static CIELab XYZtoLab(double x, double y, double z)
{
    CIELab lab = CIELab.Empty;

    lab.L = 116.0 * Fxyz( y/CIEXYZ.D65.Y ) -16;
    lab.A = 500.0 * (Fxyz( x/CIEXYZ.D65.X ) - Fxyz( y/CIEXYZ.D65.Y) );
    lab.B = 200.0 * (Fxyz( y/CIEXYZ.D65.Y ) - Fxyz( z/CIEXYZ.D65.Z) );
    
    return lab;
}
```
#### G - La\*b\* conversions

<a name="lab2" id="lab2"></a>

##### a - La\*b\* to XYZ

<a name="lab2xyz" id="lab2xyz"></a>
The conversion principle is the one below:

*d* = 6/29

![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-1.png)
![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-2.png)
![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-3.png)


if ![L*a*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-4-1.png) then ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-4-2.png)else ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-4-3.png)
if ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-5-1.png) then ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-5-2.png)else ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-5-3.png)
if ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-6-1.png) then ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-6-2.png)else ![La*b* to XYZ](https://cdn.patrickwu.space/posts/dev/color/lab2-6-3.png)


The C# equivalent is:

```csharp
/// <summary>
/// Converts CIELab to CIEXYZ.
/// </summary>
public static CIEXYZ LabtoXYZ(double l, double a, double b)
{
    double delta = 6.0/29.0;

    double fy = (l+16)/116.0;
    double fx = fy + (a/500.0);
    double fz = fy - (b/200.0);
    
    return new CIEXYZ(
        (fx > delta)? CIEXYZ.D65.X * (fx*fx*fx) : (fx - 16.0/116.0)*3*(
            delta*delta)*CIEXYZ.D65.X,
        (fy > delta)? CIEXYZ.D65.Y * (fy*fy*fy) : (fy - 16.0/116.0)*3*(
            delta*delta)*CIEXYZ.D65.Y,
        (fz > delta)? CIEXYZ.D65.Z * (fz*fz*fz) : (fz - 16.0/116.0)*3*(
            delta*delta)*CIEXYZ.D65.Z
        );
}
```

##### b - La\*b\* to RGB

<a name="lab2rgb" id="lab2rgb"></a>Nothing really new, the principle is to convert to **XYZ** and then to **RGB** :

```csharp
/// <summary>
/// Converts CIELab to RGB.
/// </summary>
public static RGB LabtoRGB(double l, double a, double b)
{
    return XYZtoRGB( LabtoXYZ(l, a, b) );
}
```
### Using the code

<a name="utc" id="utc"></a> Well, after showing you the conversion algorithms, maybe there is nothing more I can tell you.

In fact, there are many other useful methods in `ColorSpaceHelper`. You will find: 
- Average color implementation (`ColorSpaceHelper.GetColorDistance()`). 
- Wheel color generation (`ColorSpaceHelper.GetWheelColors()`) with 32bit support (alpha). 
- Light spectrum color generation for (`ColorSpaceHelper.GetSpectrumColors()`) with 32bit support (alpha). 
- Conversion to and from web colors (`ColorSpaceHelper.HexToColor()`). 
- Conversion to and from `System.Drawing.Color` and the other structures. 

Also, I am planning to write a C# class for colors for easier use in UWP and WPF apps. Hope you like it!  [link(not finished)](#)