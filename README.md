# rime-dict-creator
一个简单的可以快速根据纯文本创建 Rime 输入法词库的转换脚本

## 用法

首先需要制作一个纯文本文件（UTF-8 格式），如果是 Word，可以先 Ctrl+A，Ctrl+C 复制粘贴到记事本或 VSCode 等编辑器中保存。

文件名称为 `your-dict-name.txt`，其中 `your-dict-name` 需要自己设定，例如创建一个法律词库，你可以选名 `law.txt`。

```bash
# 首先通过 git 拉回项目，然后再进目录后执行下面的命令
composer update
php convert.php {your-dict-name}.txt
```

此脚本将会提取所有中文，并通过分词，去重，然后最后生成一个 Rime 的词库配置文件 `luna_pinyin.{your-dict-name}.dict.yaml`。

**如果你还没有配置扩展词库**，在 `luna_pinyin_simp.custom.yaml` 加入：

```yaml
patch:
  "translator/dictionary": luna_pinyin.extended
```

新建文件 `luna_pinyin.extended.dict.yaml`：

```yaml
# Rime dictionary
# encoding: utf-8
# Luna Pinyin Extended Dictionary  - 朙月拼音扩充词库

---
name: luna_pinyin.extended      # 词库名
version: "2022.01.25"
sort: by_weight                 # by_weight（按词频高低排序）或 original（保持原码表中的顺序）
use_preset_vocabulary: true     # true 或 false，选择是否导入预设词汇表【八股文】

import_tables:
  - luna_pinyin.{your-dict-name}

```

**如果已经配置了扩充字库**，则在你自己的词库中插入扩展表 `luna_pinyin.{your-dict-name}` 即可。

## 现有问题

- 多音字暂时不好搞，因为采用的是 [jifei/Pinyin](https://github.com/jifei/Pinyin) 脚本进行简单提取拼音。
- 关于分词不准确的情况，可以根据 [结巴分词 PHP](https://github.com/fukuball/jieba-php) 提供的特性进行调整，目前采用的是默认模式分词。
- 脚本不到 100 行，有闲者可以帮忙改下。
