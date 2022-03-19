---
title: Windows Phone 8 LongListSelector按拼音首字母分組
date: 2014/10/16 08:00:00
tags:
- C#
- WP8
lang: zh
---

Windows Phone 8的LongListSelector控制項按拼音分組主要有兩種方法，一個是在資料來源裡手工指定拼音首字母欄位，作為index，這種方法效率高但會造成資料冗餘不宜維護。另一個就是我今天介紹的方法，來自MSDN，雖然官網例子是針對是英文資料的首字母分組，但其實稍微改一下還是是支援中文的。

 <!--more-->

實現方法很簡單。首先你需要一個來自MSDN的AlphaKeyGroup類，程式碼如下：


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
    /// Public function Object() { [native code] }.
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

這個類裡面主要的精髓就在於：

```csharp
var slg = new SortedLocaleGrouping(ci);
```

SortedLocaleGrouping可以根據傳入的傳入的CultureInfo返回經過排序的組標頭。

要按照拼音首字母排序，我們只要傳入中國大陸的CultureInfo就可以了，也就是zh-CN。在中文環境的Windows Phone系統上，當然也可以用當前UI執行緒的CultureInfo去獲得
System.Threading.Thread.CurrentThread.CurrentUICulture

但是為了保證我們的拼音排序能在任何語言設定下都統一，我還是建議寫死zh-CN在裡面。

在我的應用裡，我需要按地鐵站點(Station類)的地鐵站名首字母(Station.StationName)分組，所以我繫結的集合要用AlphaKeyGroup包一下：

```csharp
public ObservableCollection<AlphaKeyGroup<Station>> GroupedStations { ... }
```
然後，在給這個集合賦值的地方寫Group的具體邏輯：

```csharp
GroupedStations = AlphaKeyGroup<Station>.CreateGroups(
    AllStations,
    new CultureInfo("zh-CN"),
    s => s.StationName.Substring(0, 1),
    true).ToObservableCollection();
```

第一個參數AllStations是原始資料，一個普通的IEnumerable<Station>集合。

第二個參數是最重要的，按哪種Culture進行分組，一定要傳入zh-CN，簡體中文。

第三個參數是個lambda表示式，這個委託負責分組欄位的具體邏輯，在這裡我們要按Station.StationName的第一個字的拼音首字母排序，所以需要取Substring(0,1)，返回Station.StationName的第一個字，之後SortedLocaleGrouping就可以自動進行拼音首字母分組了。

第四個參數列示經過分組的排序結果需不需要排序，true表示需要排序，這也是我們通常的需求。

至此，後端程式碼的工作就全部搞定了。前臺xaml上的資料繫結還是和普通的LongListSelector沒啥區別。

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

App.xaml裡的Style:

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
轉自：http://diaosbook.com/Post/2014/2/22/longlistselector-group-by-py-wp8