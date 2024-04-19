<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii Form</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/form/v/stable.png)](https://packagist.org/packages/yiisoft/form)
[![Total Downloads](https://poser.pugx.org/yiisoft/form/downloads.png)](https://packagist.org/packages/yiisoft/form)
[![Build status](https://github.com/yiisoft/form/workflows/build/badge.svg)](https://github.com/yiisoft/form/actions?query=workflow%3Abuild)
[![Code Coverage](https://codecov.io/gh/yiisoft/form/graph/badge.svg?token=7JVVOMMKCZ)](https://codecov.io/gh/yiisoft/form)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fform%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/form/master)
[![static analysis](https://github.com/yiisoft/form/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/form/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/form/coverage.svg)](https://shepherd.dev/github/yiisoft/form)

The package helps with implementing data entry forms.

## Requirements

- PHP 8.1 or higher.
- `mbstring` PHP extension.

## Installation

The package could be installed with composer:

```shell
composer require yiisoft/form --prefer-dist
```

## Documentation

- English

  - [Creating Forms](docs/guide/en/creating-forms.md)
  - [Fields Configuration](docs/guide/en/fields-configuration.md)
  - [Creating and Using Custom Fields](docs/guide/en/creating-fields.md)
  
  Fields available out of the box:
  
  - [Button](docs/guide/en/fields/button.md)
  - [ButtonGroup](docs/guide/en/fields/button-group.md)
  - [Checkbox](docs/guide/en/fields/checkbox.md)
  - [CheckboxList](docs/guide/en/fields/checkbox-list.md)
  - [Date](docs/guide/en/fields/date.md)
  - [DateTime](docs/guide/en/fields/date-time.md)
  - [DateTimeLocal](docs/guide/en/fields/date-time-local.md)
  - [Email](docs/guide/en/fields/email.md)
  - [ErrorSummary](docs/guide/en/fields/error-summary.md)
  - [Fieldset](docs/guide/en/fields/fieldset.md)
  - [File](docs/guide/en/fields/file.md)
  - [Hidden](docs/guide/en/fields/hidden.md)
  - [Image](docs/guide/en/fields/image.md)
  - [Number](docs/guide/en/fields/number.md)
  - [Password](docs/guide/en/fields/password.md)
  - [RadioList](docs/guide/en/fields/radio-list.md)
  - [Range](docs/guide/en/fields/range.md)
  - [ResetButton](docs/guide/en/fields/reset-button.md)
  - [Select](docs/guide/en/fields/select.md)
  - [SubmitButton](docs/guide/en/fields/submit-button.md)
  - [Telephone](docs/guide/en/fields/telephone.md)
  - [Text](docs/guide/en/fields/text.md)
  - [Textarea](docs/guide/en/fields/textarea.md)
  - [Url](docs/guide/en/fields/url.md)
  
  Field parts:
  
  - [Error](docs/guide/en/field-parts/error.md)
  - [Hint](docs/guide/en/field-parts/hint.md)
  - [Label](docs/guide/en/field-parts/label.md)

- Portuguese - Brazil

  - [Creating Forms](docs/guide/pt-BR/creating-forms.md)
  - [Fields Configuration](docs/guide/pt-BR/fields-configuration.md)
  - [Creating and Using Custom Fields](docs/guide/pt-BR/creating-fields.md)
  
  Fields available out of the box:
  
  - [Button](docs/guide/pt-BR/fields/button.md)
  - [ButtonGroup](docs/guide/pt-BR/fields/button-group.md)
  - [Checkbox](docs/guide/pt-BR/fields/checkbox.md)
  - [CheckboxList](docs/guide/pt-BR/fields/checkbox-list.md)
  - [Date](docs/guide/pt-BR/fields/date.md)
  - [DateTime](docs/guide/pt-BR/fields/date-time.md)
  - [DateTimeLocal](docs/guide/pt-BR/fields/date-time-local.md)
  - [Email](docs/guide/pt-BR/fields/email.md)
  - [ErrorSummary](docs/guide/pt-BR/fields/error-summary.md)
  - [Fieldset](docs/guide/pt-BR/fields/fieldset.md)
  - [File](docs/guide/pt-BR/fields/file.md)
  - [Hidden](docs/guide/pt-BR/fields/hidden.md)
  - [Image](docs/guide/pt-BR/fields/image.md)
  - [Number](docs/guide/pt-BR/fields/number.md)
  - [Password](docs/guide/pt-BR/fields/password.md)
  - [RadioList](docs/guide/pt-BR/fields/radio-list.md)
  - [Range](docs/guide/pt-BR/fields/range.md)
  - [ResetButton](docs/guide/pt-BR/fields/reset-button.md)
  - [Select](docs/guide/pt-BR/fields/select.md)
  - [SubmitButton](docs/guide/pt-BR/fields/submit-button.md)
  - [Telephone](docs/guide/pt-BR/fields/telephone.md)
  - [Text](docs/guide/pt-BR/fields/text.md)
  - [Textarea](docs/guide/pt-BR/fields/textarea.md)
  - [Url](docs/guide/pt-BR/fields/url.md)
  
  Field parts:
  
  - [Error](docs/guide/pt-BR/field-parts/error.md)
  - [Hint](docs/guide/pt-BR/field-parts/hint.md)
  - [Label](docs/guide/pt-BR/field-parts/label.md)

Testing:

- More information can be found in the [Internals.](docs/internals.md)

## Support

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Access is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
