# C语言常用字符串操作函数 #

----------
```
#include <string.h>

size_t strlen(const char *s);
size_t strnlen(const char *s, size_t maxlen);

char *strcat(char *dest, const char *src);
char *strncat(char *dest, const char *src, size_t n);

int strcmp(const char *s1, const char *s2);
int strncmp(const char *s1, const char *s2, size_t n);

char *strcpy(char *dest, const char *src);
char *strncpy(char *dest, const char *src, size_t n);

char *strstr(const char *haystack, const char *needle);
char *strchr(const char *s, int c);
char *strrchr(const char *s, int c);
```

前面5组函数都有一个共同特点，第二个函数名比前一个多了个n。先不管这个n的具体含义，我们直接开始讲解函数。

# strlen()函数 #
见名知意，这个函数是用来获取字符串长度的，准确来讲是除去结尾空字符的长度。下面来看一个例子:
```
#include<stdio.h>
#include<string.h>

//截短字符串
void fit(char *str, size_t n);

int main(void)
{
	char msg[] = "Hold on to your hats, hackers. ";
	puts(msg);
	
	fit(msg, 7);
	puts(msg);
	
	puts("Let's look at some more of the string. ");
	
	puts(msg + 8);
	
	return 0;
}

void fit(char *str, size_t n)
{
	if (strlen(str) > n)
		*(str + n) = '\0';
}
```

声明一个fit()函数用来截短一个字符串到指定长度，参数str没有使用const修饰，即表示字符串可以被fit()函数改变。第二个参数n表示截取后，字符串的长度。在主函数中声明了一个msg字符数组。调用fit(msg, 7)，其作用就是*(msg+7) = '\0'，也就是把msg字符数组第8个元素变成空字符'\0'，让它所表示的字符串提前结束，忽略后面的元素，然而，数组的后面的其他元素仍然存在，puts(msg+8)输出了被截的内容。

# strnlen()函数 #
strnlen()等同strlen()函数，但是返回的值不会超过maxlen。也就是说strnlen()函数中会计算前maxlen个字符，不会超过s+maxlen这个位置。下面我们自己来实现这个函数试试看：
```
#include<stdio.h>
#include<string.h>

size_t strnlen(const char *s, size_t maxlen);

int main(void)
{
	char msg[] = "Hold on to your hats, hackers. ";
	size_t n = strlen(msg);
	size_t m = strnlen(msg, 30);
	
	printf("strlen is %u\n", n);
	printf("strnlen is %u\n", m);
}

size_t strnlen(const char *s, size_t maxlen)
{
	size_t n = 0;
	
	while(*s && n < maxlen) {
		s++;
		n++;
	}
	return n;
}
```
输出结果：
``` 
strlen is 31
strnlen is 30
```

注释掉我们自己定义的strnlen()函数，使用string.h的定义，再编译执行，结果仍然一样。

# strcat()函数 #
这个函数用来拼接字符串，它将复制第二个字符串的拷贝添加到第一个字符串的结尾，第二个字符串没有改变，第一个字符串成为一个新的组合字符串了。所以第二个参数用const修饰了。函数返回第一个参数的值。那么问题就来了，如果第一个字符串的存储空间不够大，字符串就会溢出，产生不安全的行为。strncat()与之不同，它把src所指字符串的前n个字符添加到dest结尾处，覆盖dest结尾处的'/0'，实现字符串连接。所以使用strcat，要程序员自己保证dest足够容纳拼接后的整个字符串，而strncat使用n参数来限制只复制dest空闲的容量大小的字符。strncat()的一个简单实现也许是这样：
```
char *strncat(char *dest, const char *src, size_t n)
{
	size_t dest_len = strlen(dest);
	size_t i;
	for (i = 0 ; i < n && src[i] != '\0' ; i++)
		dest[dest_len + i] = src[i];
	
	dest[dest_len + i] = '\0';
	
	return dest;
}
```

# strcmp()函数 #
这个函数用来比较2个字符串s1,s2的大小，如果s1和s2相同，返回为0，s1小于s2，返回负数，否则返回大于0。对于返回数值的大于，不同实现可能不同。有的是-1, 0, 1这三个值，也有的可能是字符串某个位置上的ASCII值的差。而strncmp()的n参数用来限定，只比较s1的前n个字符，而不是一直比较找到不同的字符。因些strncpm(s1, s2, strlen(s2)) == 0这个表达式可以用来判断s1是否以s2开头。当然也可以使用strstr()的返回值来判断。下面的例子程序展示了strncmp()函数的一个用途：
```
#include<stdio.h>
#include<string.h>
#define LIST_SIZE 5

int main(void)
{
	char *list[LIST_SIZE] = {"astronomy", "astoudfing", "astrophysici", "ostracize", "asterism"};
	const char *str = "astro";
	
	int i, count = 0;
	
	for (i = 0; i < LIST_SIZE; i++) {
		if (strncmp(list[i], str, strlen(str)) == 0) {
			printf("Found: %s\n", list[i]);
			count++;
		}
	}
	
	printf("The list contained %d words beginning with astro.\n", count);
	
	return 0;
}

```

# strcpy()函数 #
如果要复制一个字符串，即在内存中产生某个字符串的副本，你需要使用strcpy()函数来实现。它的思路有点像strcat()函数，只不过不是拼按在dest的后面，而是直接从dest指针处开始覆盖，同时也会把src结尾的空字符复制。同样要求dest有足够的空间。strncpy()用来限制只复制src前n个字符，如果前n个字符没有空字符，dest字符串将没有结尾的空字符。如果src长度不够n，strncpy()会写入额外的空字符到dest中，确保n个字符被定入。strncpy()的一个简单实现如下：
```
char *strncpy(char *dest, const char *src, size_t n)
{
	size_t i;

	for (i = 0; i < n && src[i] != '\0'; i++)
		dest[i] = src[i];
	for ( ; i < n; i++)
		dest[i] = '\0';
		
	return dest;
}
```
# strstr()函数 #
strstr()函数在haystack搜索子串needle，字符串的结尾空字符不会参与比较。返回子串needle第一次匹配时的开始位置的指针。
