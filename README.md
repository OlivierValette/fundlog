# fundlog: portfolio management app

This website was developed for the final project of the DC-Dev training cursus.
DC-Dev is a web developer training course provided by Digital Campus in Rennes (France) over 9 months.

**fundlog** is a portfolio management app for mutual funds, 
in french for the time being.
Users can manage all their portfolios in one place, 
prepare and valid transactions, set alerts 
and more to come... 

## Install
```shell
git clone fundlog
composer install
npm install
```
create a `.env.local` (and perhaps a `.env.prod.local`) file
with convenient parameters for database access and mailer. 

## Run
Get last version
```shell
git fetch origin
git status
```
will report something like:
```shell
Your branch is behind 'origin/master' by 1 commit, and can be fast-forwarded.
```
Then
```shell
git pull
```
Edit `webpack.config.js` to switch `setPublicPath` from dev to prod version 
```js
// dev
.setPublicPath('/php/patinoire/fundlog/public/build')
// prod
//.setPublicPath('/build')
```
Still not sure if next step is mandatory :§
```shell
composer dump-env prod
```
Build
```shell
npm run build
```
