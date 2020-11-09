
class ISource:
    """
     * Source of values to parse
    """

    def get(self):
        raise NotImplementedError('TBA')

    def post(self):
        raise NotImplementedError('TBA')

    def files(self):
        raise NotImplementedError('TBA')

    def session(self):
        raise NotImplementedError('TBA')

    def server(self):
        raise NotImplementedError('TBA')

    def env(self):
        raise NotImplementedError('TBA')
