FROM bitnami/drupal:latest
LABEL description="Aurora Project"

ENV PATH="/opt/bitnami/git/bin:$PATH"

RUN install_packages patch

RUN bitnami-pkg install git-2.14.1-0

COPY rootfs /

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/run.sh"]
