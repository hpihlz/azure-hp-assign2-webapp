# Variables
RESOURCE_GROUP="AzureWebApp-RG"
ACR_NAME="azurewebappregistry"    # must be unique
LOCATION="northeurope"        # your region

# Create the registry
az acr create \
  --resource-group $RESOURCE_GROUP \
  --name $ACR_NAME \
  --sku Basic \
  --location $LOCATION