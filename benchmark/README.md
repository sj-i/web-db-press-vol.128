# 概要
- [symfony/symfony-demo](https://github.com/symfony/demo) 2.0.2 の特定ページを繰り返し取得する単純なベンチマークについて、PHP8.1を使い以下3環境で比較した結果です
  - RoadRunner
  - Apache(prefork) + mod_php
  - Nginx + php-fpm
- 記事中でも言及している通り、サーバの本当の性能は実際のアプリケーションで現実的な負荷をかけることでしかわからず、そこまで意味のある比較ではありません
  - 特にボトルネックがネットワーク I/O などブートストラップ以外の部分にあるようなアプリケーション / 実行環境では、大きく異なる結果が出ることでしょう

# 環境立ち上げ方法

```bash
docker-compose up -d
```

# 環境終了方法

```bash
docker-compose down
```

# 検証コマンド
- それぞれ暖機のため先行して 2 回分実行しています

## RoadRunner
```bash
ab -c 50 -n 1000 http://localhost:8080/ja/blog/
```

## Apache(prefork) + mod_php
```bash
ab -c 50 -n 1000 http://localhost:8081/ja/blog/
```

## Nginx + php-fpm
```bash
ab -c 50 -n 1000 http://localhost:8082/ja/blog/
```

# 検証に使ったマシン
- Lenovo IdeaPad Flex 550 14ARE05
  - CPU: AMD Ryzen 4700U (8 core/ 8 threads)
  - Memory: 16GB
  - Storage: SSD, M.2 PCIe-NVMe
- Ubuntu 21.04

# 結果
- 詳細結果は [results](./results/) に

<table>
<tr>
<th>環境</th><th>RPS</th><th>最小</th><th>平均</th><th>標準偏差</th><th>中央値</th><th>最大</th><th>75％</th><th>90％</th><th>95％</th>
</tr>
<tr>
<th>mod_php+prefork</th><td>217.93</td><td>35</td><td>223</td><td>28.4</td><td>226</td><td>291</td><td>233</td><td>240</td><td>244</td>
</tr>
<tr>
<th>nginx+php-fpm</th><td>217.62</td><td>32</td><td>223</td><td>29.5</td><td>224</td><td>309</td><td>235</td><td>249</td><td>258</td>
</tr>
<tr>
<th>RoadRunner</th><td>519.21</td><td>13</td><td>94</td><td>20.5</td><td>91</td><td>269</td><td>95</td><td>110</td><td>132</td>
</tr>
</table>

# 条件設定の補足
- マシン起動後しばらくして落ち着いてからの、なるべく他の仕事をしていない安定した条件で確認していますが、それでもマシン利用状況による多少のバラつきはありえます
  - mod_php と fpm の間には大差がないため、多少差が開いたり逆転したりすることもあるかもしれません
    - が、とにかく大幅に変わることはなく、RoadRunner の方が圧倒的に速くなる条件なのは間違いありません
- 結果が静的ファイルの配信性能比較に近付かないよう、twig のキャッシュをオフにしています
  - 有効にすると mod_php と fpm ともに 300 RPS 程度出ます
  - 結果的にボトルネックがオートロード / ブートストラップ部分へ寄り、RoadRunner に特に有利な状況設定とはなっています
- CPU にあわせて各環境のワーカープロセス数は 8 に設定しています
- Nginx + php-fpm は Unix ドメインソケットでの接続にしています
- 各環境で opcache を有効にしています
  - この例では opcache のコード最適化が有効に働いており、RoadRunner で opcache を切った場合の RPS は 300 程度まで落ちます