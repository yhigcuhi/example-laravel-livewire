# nginx
FROM nginx

# タイムゾーン 日本設定
ENV TZ=Asia/Tokyo
# システム時間等の書き換え
RUN echo "${TZ}" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# nginx config file
COPY ./*.conf /etc/nginx/conf.d/
COPY ./*.conf.template /etc/nginx/conf.d/
COPY ./security/*.conf /etc/nginx/conf.d/security/

# ドキュメントディレクトリパス
WORKDIR /var/www/html