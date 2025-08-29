#!/usr/bin/env bash
export WP_API_URL='http://localhost:10008'
export WP_API_USERNAME='selfscandev'
export WP_API_PASSWORD='3VuP s6nl FDxs Z2xo B1mp ZEPk'
exec npx -y @automattic/mcp-wordpress-remote@0.2.5 --transport stdio
