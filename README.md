# Laravel User Payment Example

Laravelでユーザーモデルを利用した決済処理の実装例です。

## 構造

```
app/
├── Models/
│   └── User/
│       ├── User.php                  # ユーザーメインクラス
│       └── Trait/
│           └── HasStripePayment.php  # Stripe決済用トレイト
└── Http/
    └── Controllers/
        └── OrderController.php       # 注文処理コントローラー
```

## 特徴

- User関連の処理を専用フォルダに集約
- トレイトを使用した決済処理の分離
- シンプルなエラーハンドリング
- トランザクション管理の実装

## 使用方法

1. Userエイリアスの設定（config/app.php）
2. Stripeの設定
3. データベースのセットアップ

## セットアップ

```bash
composer require stripe/stripe-php
```

## 注意事項

- このコードは実装例です。本番環境で使用する場合は、セキュリティやエラーハンドリングを強化してください。
- Stripe APIキーは必ず環境変数で管理してください。