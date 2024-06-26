# docker-laravel-portfolioについて
- 想定ユーザー
    - 特定保健指導対象者の定期健康診断結果CSVを元に運営チームが管理する想定
- 利用シーン
    - 一覧画面にて定期健康診断結果の内容を絞り込みし、対象者にお知らせメール送信を実施

## 実装手順
- Dockerとdocker-composeを利用して開発環境を構築
    - 参考記事
        - https://qiita.com/ucan-lab/items/56c9dc3cf2e6762672f4
        - (キャッシュを使用せずビルド)　docker-compose build --no-cache
        - (コンテナ起動)　docker-compose up -d
        - (コンテナに入る)　docker compose exec app bash
        - [http://localhost:8084/](http://localhost:8084/)
- **データモデルの設計**
    - CSVファイルの項目からカラム名を決定
    - 特定保健指導対象者の定期健康診断結果を管理する想定のため、`health_check_results`テーブル用のマイグレーションを作成し、対応するモデルを実装
- **CSVの読み込みと処理**
    - Laravel Artisan コマンドを使用してモデル、マイグレーション、リソースコントローラを生成：**`php artisan make:model HealthCheckResult --migration --controller --resource`**
    - ビューのディレクトリとファイルを準備：**`mkdir resources/views/health_check_results`** および **`touch resources/views/health_check_results/import.blade.php`**
    - レイアウトの共通化ファイルを作成
        - 今回、時間がなかったため実装コストの削減と見た目の綺麗さの担保のためにBootstrapを利用しました。
    - 一覧画面にファイルを選択してアップロードするためのフォームを実装
    - CSVファイルのパース
        - 参考記事
            - [https://qiita.com/niconiconainu/items/bc8d0278bee99ae1f2ec](https://qiita.com/sola-msr/items/4a114d66bb5ca48d869c)
        - パースしてモデルに詰め込む部分の実装
        - DBに保存部分の実装
- 一覧データの表示及び検索画面の実装
    - Blade返却用Controller 及び データ取得API用のControllerの作成
        - データ読み込み部分及びテンプレートへの受け渡し部分実装
    - 検索フォーム実装
        - Bladeテンプレートと合わせてvanillaJSを使用
        - 検索結果を動的にリアルタイムに表示する機能とのことで、ajaxを利用してテーブルのデータを書き換える
        - 検索対象
            - 保険証記号、保険証番号、漢字氏名、カナ（姓） 、カナ（名）、メールアドレス
        - ページネーションも同様の仕組みで対応
- メール送信
    - メールについてキャッチアップ
        - 参考ドキュメント
            - https://readouble.com/laravel/9.x/ja/mail.html
    - MailHog設定
        - ローカルでのメール送信とMailHogでの受信確認
- バッチ処理の実装
    - 毎日10:00に自動的に、パースしたデータを基に読み込んだメールアドレス宛に送り、メールアドレスがない場合は処理をスキップ
    - cronの設定
        - Laravel Artisan コマンドを使用してコマンドファイル生成：`php artisan make:command SendEmailsDaily`
        - コンテナ内からcronを実行
            - 参考記事
                - https://qiita.com/messhii222/items/fccc7705f05ff96260cf
                - https://qiita.com/rerofumi/items/fc0126c4e985b78f769b
            - crontabで1分ごとにLaravelのスケジューラ起動
            - スケジューラは、`schedule`メソッドで定義されたスケジュールをチェック
            - 毎日10時になると、`emails:send-daily`コマンド実行
### 開発環境構築後、[http://localhost:8084/](http://localhost:8084/)にアクセスしCSVをアップロード時の、想定表示画面
![FireShot Capture 286 -  - localhost](https://github.com/igawa1001/docker-laravel-portfolio/assets/36191355/b9f4560f-b0a4-4599-ada4-0505f5e94033)

### MailHog受信確認
![image](https://github.com/igawa1001/docker-laravel-portfolio/assets/36191355/eb54c3d0-93e6-41f6-82eb-738ccd43c4ca)

