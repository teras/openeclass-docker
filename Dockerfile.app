FROM docker.io/nginx:alpine

ARG mversion=3.14
ARG version=3.14.1

RUN wget https://download.openeclass.org/files/${mversion}/openeclass-${version}.tar.gz && \
    tar xf /openeclass-${version}.tar.gz && mv /openeclass-${version} /openeclass && \
    rm /openeclass-${version}.tar.gz

RUN chown -R nginx:nginx /openeclass/ #rm -rf /openeclass/config && mkdir /openeclass/config && mkdir /openeclass/courses && mkdir /openeclass/video && chmod -R 777 /openeclass/config && chmod -R 777 /openeclass/courses && chmod -R 777 /openeclass/video

COPY ./openeclass.conf /etc/nginx/conf.d/default.conf
