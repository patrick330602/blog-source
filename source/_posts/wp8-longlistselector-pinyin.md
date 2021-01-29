---
title: Windows Phone 8 LongListSelector按拼音首字母分组
date: 2014/10/16 08:00:00
tags:
- C#
- WP8
---

Windows Phone 8的LongListSelector控件按拼音分组主要有两种方法，一个是在数据源里手工指定拼音首字母字段，作为index，这种方法效率高但会造成数据冗余不宜维护。另一个就是我今天介绍的方法，来自MSDN，虽然官网例子是针对是英文数据的首字母分组，但其实稍微改一下还是是支持中文的。

 <!--more-->

实现方法很简单。首先你需要一个来自MSDN的AlphaKeyGroup类，代码如下：


```csharp
public class AlphaKeyGroup<T> : List<T>{
    /// <summary>
    /// The delegate that is used to get the key information.
    /// </summary>
    /// <param name="item">An object of type T</param>
    /// <returns>The key value to use for this object</returns>
    public delegate string GetKeyDelegate(T item);
    /// <summary>
    /// The Key of this group.
    /// </summary>
    public string Key { get; private set; }
    /// <summary>
    /// Public constructor.
    /// </summary>
    /// <param name="key">The key for this group.</param>
    public AlphaKeyGroup(string key)
    {
        Key = key;
    }
    /// <summary>
    /// Create a list of AlphaGroup<T> with keys set by a SortedLocaleGrouping.
    /// </summary>
    /// <param name="slg">The </param>
    /// <returns>Theitems source for a LongListSelector</returns>
    private static List<AlphaKeyGroup<T>> CreateGroups(SortedLocaleGrouping slg)
    {
        return slg.GroupDisplayNames.Select(key => new AlphaKeyGroup<T>(key)).ToList();
    }
    /// <summary>
    /// Create a list of AlphaGroup<T> with keys set by a SortedLocaleGrouping.
    /// </summary>
    /// <param name="items">The items to place in the groups.</param>
    /// <param name="ci">The CultureInfo to group and sort by.</param>
    /// <param name="getKey">A delegate to get the key from an item.</param>
    /// <param name="sort">Will sort the data if true.</param>
    /// <returns>An items source for a LongListSelector</returns>
    public static List<AlphaKeyGroup<T>> CreateGroups(IEnumerable<T> items, CultureInfo ci, GetKeyDelegate getKey, bool sort)
    {
        var slg = new SortedLocaleGrouping(ci);
        var list = CreateGroups(slg);
        foreach (var item in items)
        {
            var index = 0;
            if (slg.SupportsPhonetics)
            {
                //check if your database has yomi string for item
                //if it does not, then do you want to generate Yomi or ask the user for this item.
                //index = slg.GetGroupIndex(getKey(Yomiof(item)));
            }
            else
            {
                index = slg.GetGroupIndex(getKey(item));
            }
            if (index >= 0 && index < list.Count)
            {
                list[index].Add(item);
            }
        }
        if (!sort) return list;
        foreach (var group in list)
        {
            @group.Sort((c0, c1) => ci.CompareInfo.Compare(getKey(c0), getKey(c1)));
        }
        return list;
    }}
```

这个类里面主要的精髓就在于：

```csharp
var slg = new SortedLocaleGrouping(ci);
```

SortedLocaleGrouping可以根据传入的传入的CultureInfo返回经过排序的组标头。

要按照拼音首字母排序，我们只要传入中国大陆的CultureInfo就可以了，也就是zh-CN。在中文环境的Windows Phone系统上，当然也可以用当前UI线程的CultureInfo去获得
System.Threading.Thread.CurrentThread.CurrentUICulture

但是为了保证我们的拼音排序能在任何语言设置下都统一，我还是建议写死zh-CN在里面。

在我的应用里，我需要按地铁站点(Station类)的地铁站名首字母(Station.StationName)分组，所以我绑定的集合要用AlphaKeyGroup包一下：

```csharp
public ObservableCollection<AlphaKeyGroup<Station>> GroupedStations { ... }
```
然后，在给这个集合赋值的地方写Group的具体逻辑：

```csharp
GroupedStations = AlphaKeyGroup<Station>.CreateGroups(
    AllStations,
    new CultureInfo("zh-CN"),
    s => s.StationName.Substring(0, 1),
    true).ToObservableCollection();
```

第一个参数AllStations是原始数据，一个普通的IEnumerable<Station>集合。

第二个参数是最重要的，按哪种Culture进行分组，一定要传入zh-CN，简体中文。

第三个参数是个lambda表达式，这个委托负责分组字段的具体逻辑，在这里我们要按Station.StationName的第一个字的拼音首字母排序，所以需要取Substring(0,1)，返回Station.StationName的第一个字，之后SortedLocaleGrouping就可以自动进行拼音首字母分组了。

第四个参数表示经过分组的排序结果需不需要排序，true表示需要排序，这也是我们通常的需求。

至此，后端代码的工作就全部搞定了。前台xaml上的数据绑定还是和普通的LongListSelector没啥区别。

XAML:

```xml
<phone:LongListSelector
    x:Name="StationListSelector"
    ItemsSource="{Binding GroupedStations, Mode=TwoWay}" 
    JumpListStyle="{StaticResource StationListJumpListStyle}"
    IsGroupingEnabled="True"
    HideEmptyGroups="True"
    SelectionChanged="SelectStation"
    toolkit:TiltEffect.IsTiltEnabled="True">
    <phone:LongListSelector.GroupHeaderTemplate>
        <DataTemplate>
            <Border 
                BorderBrush="{StaticResource PhoneAccentBrush}"
                Padding="15,0"
                Width="75"
                Height="75"
                Margin="0,0,0,10"
                BorderThickness="2"
                HorizontalAlignment="Left" 
                    Background="White">
                <TextBlock Text="{Binding Key}" 
                           FontSize="48"
                           Foreground="{StaticResource PhoneAccentBrush}">
                </TextBlock>
            </Border>
        </DataTemplate>
    </phone:LongListSelector.GroupHeaderTemplate>
```

App.xaml里的Style:

```xml
<Style x:Key="StationListJumpListStyle" TargetType="phone:LongListSelector">
    <Setter Property="GridCellSize"  Value="113,113"/>
    <Setter Property="LayoutMode" Value="Grid" />
    <Setter Property="ItemTemplate">
        <Setter.Value>
            <DataTemplate>
                <Border Background="{StaticResource PhoneAccentBrush}" Width="113" Height="113" Margin="6" >
                    <TextBlock Text="{Binding Key}" FontFamily="{StaticResource PhoneFontFamilySemiBold}" FontSize="48" Padding="6"
       Foreground="{StaticResource PhoneForegroundBrush}" VerticalAlignment="Center"/>
                </Border>
            </DataTemplate>
        </Setter.Value>
    </Setter></Style>
```
转自：http://diaosbook.com/Post/2014/2/22/longlistselector-group-by-py-wp8