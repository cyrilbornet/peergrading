<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
    exclude-result-prefixes="xs"
    version="2.0">
    <xsl:template match="channel/item">
        <xsl:if test="wp:post_type='post'">
        <xsl:value-of select="dc:creator"></xsl:value-of>
        |
        <xsl:value-of select="title"></xsl:value-of>
        |
        <xsl:value-of select="link"></xsl:value-of>
        |
        <xsl:value-of select="wp:post_id"></xsl:value-of>
        ---
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>