FROM bitnami/drupal:latest
LABEL description="Aurora Project"

COPY rootfs /

COPY profile/auroraprj/ /opt/auroraprj/

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/run.sh"]
