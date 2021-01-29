---
layout: post
title: Byte[],Stream,Ibuffer,IRandomAccessStream的互相轉換
date: 2014/9/7 20:46:25
tags:
- C#
---

今天做8.1程序時徹底弄混了IRandomAcessStream和Stream。。。於是乎，我找了一個可用的不同類型轉換。。。。

### Stream 轉IRandomAccessStream

#### 方法一：

```csharp
byte[] bytes = StreamToBytes(stream);
InMemoryRandomAccessStream memoryStream = new InMemoryRandomAccessStream();
DataWriter datawriter = new DataWriter(memoryStream.GetOutputStreamAt(0));
datawriter.WriteBytes(bytes);
await datawriter.StoreAsync();
```


 <!--more-->


#### 方法二：

```csharp
var randomAccessStream = new InMemoryRandomAccessStream();
var outputStream = randomAccessStream.GetOutputStreamAt(0);
await RandomAccessStream.CopyAsync(stream.AsInputStream(), outputStream);
```

### IRandomAccessStream 轉 Stream

```csharp
Stream stream=WindowsRuntimeStreamExtensions.AsStreamForRead(randomStream.GetInputStreamAt(0));
```

### Ibuffer轉Stream

```csharp
Stream stream = WindowsRuntimeBufferExtensions.AsStream(buffer);
```

### Stream轉Ibuffer

```csharp
 MemoryStream memoryStream = new MemoryStream();           

 if (stream != null)
 {
  byte[] bytes = ReadFully(stream);
  if (bytes != null)
  {
       var binaryWriter = new BinaryWriter(memoryStream);
       binaryWriter.Write(bytes);
   }
}
IBuffer buffer=WindowsRuntimeBufferExtensions.GetWindowsRuntimeBuffer(memoryStream,0,(int)memoryStream.Length);
```

### Ibuffer轉byte[]

```csharp
byte[] bytes=WindowsRuntimeBufferExtensions.ToArray(buffer,0,(int)buffer.Length);
```

### Byte[]轉Ibuffer

```csharp
WindowsRuntimeBufferExtensions.AsBuffer(bytes,0,bytes.Length);
```

### Ibuffer轉IrandomAccessStream

```csharp
InMemoryRandomAccessStream inStream = new InMemoryRandomAccessStream();
DataWriter datawriter = new DataWriter(inStream.GetOutputStreamAt(0));
datawriter.WriteBuffer(buffer,0,buffer.Length);

await datawriter.StoreAsync();
```

### IrandomAccessStream轉Ibuffer

```csharp
Stream stream=WindowsRuntimeStreamExtensions.AsStreamForRead(randomStream.GetInputStreamAt(0));
MemoryStream memoryStream = new MemoryStream();           
if (stream != null)
{

  byte[] bytes = ReadFully(stream);
  if (bytes != null)
  {
     var binaryWriter = new BinaryWriter(memoryStream);
     binaryWriter.Write(bytes);
  }
}
IBuffer buffer=WindowsRuntimeBufferExtensions.GetWindowsRuntimeBuffer(memoryStream,0,(int)memoryStream.Length);
```
### IRandomAccessStream轉FileInputStream

```csharp
FileInputStream inputStream=randomStream.GetInputStreamAt(0) as FileInputStream;
```

### IRandomAccessStream轉FileOutputStream

```csharp
FileOutputStream outStream= randomStream.GetOutputStreamAt(0) as FileOutputStream;
```

### Stream轉byte[]

```csharp
 public static byte[] ConvertStreamTobyte(Stream input)
    {
        byte[] buffer = new byte[16 * 1024];
        using (MemoryStream ms = new MemoryStream())
            {
                int read;
                while ((read = input.Read(buffer, 0, buffer.Length)) > 0)
                {
                    ms.Write(buffer, 0, read);
                }
            return ms.ToArray();
            }
    }
```

### Byte轉Stream

```csharp
public Stream BytesToStream(byte[] bytes)
    {
        Stream stream = new MemoryStream(bytes);
        return stream;
    }
```

### Stream轉MemoryStream

```csharp
public static MemoryStream ConvertStreamToMemoryStream(Stream stream)
{
        MemoryStream memoryStream = new MemoryStream();
        if (stream != null)
        {
            byte[] buffer = ReadFully(stream);
            if (buffer != null)
            {
                var binaryWriter = new BinaryWriter(memoryStream);
                binaryWriter.Write(buffer);
            }
        }
        return memoryStream;
    }
```

### IrandomAccessStream轉byte[]

```csharp
Stream stream = WindowsRuntimeStreamExtensions.AsStreamForRead(randomStream.GetInputStreamAt(0));
MemoryStream ms = new MemoryStream();
await stream.CopyToAsync(ms);
byte[] bytes = ms.ToArray();
```