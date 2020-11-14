
from kw_input.entries import IEntry
from kw_input.sources import ISource
from kw_input.parsers import Factory as ParserFactory
from kw_input.loaders import Factory as LoaderFactory


class IInputs:
    """
     * Basic interface which tells us what actions are by default available by inputs
    """

    def set_source(self, source=None):
        """
         * Setting the variable sources - from cli (argv), _GET, _POST, _SERVER, ...
        """
        raise NotImplementedError('TBA')

    def load_entries(self):
        """
         * Load entries from source into the local entries which will be accessible
         * These two calls came usually in pair
         *
         * input.set_source(sys.argv).load_entries()
        """
        raise NotImplementedError('TBA')

    def get_in(self, entry_key: str = None, entry_sources = None):
        """
         * Get iterator of local entries, filter them on way
         * @param string|null $entry_key
         * @param string[] $entry_sources array of constants from Entries.IEntry.SOURCE_*
         * @return iterator
         * @see Entries.IEntry.SOURCE_CLI
         * @see Entries.IEntry.SOURCE_GET
         * @see Entries.IEntry.SOURCE_POST
         * @see Entries.IEntry.SOURCE_FILES
         * @see Entries.IEntry.SOURCE_SESSION
         * @see Entries.IEntry.SOURCE_SERVER
         * @see Entries.IEntry.SOURCE_ENV
        """
        raise NotImplementedError('TBA')

    def into_key_object_array(self, entries):
        """
         * Reformat iterator from get_in() into array with key as array key and value with the whole entry
         * @param iterator entries
         * @return Entries.IEntry[]
         * Also usually came in pair with previous call - but with a different syntax
         * Beware - due any dict limitations there is a limitation that only the last entry prevails
         *
         * entries = input.into_key_object_array(input.get_in('example', [Entries.IEntry.SOURCE_GET]));
        """
        raise NotImplementedError('TBA')


class Inputs(IInputs):
    """
     * Base class for passing info from inputs into objects
    """

    def __init__(self):
        self._entries = []
        self._source = None
        self._parser_factory = ParserFactory()
        self._loader_factory = LoaderFactory()

    def set_source(self, source=None):
        if source and isinstance(source, ISource):
            self._source = source
        elif hasattr(self._source, 'set_cli') \
                and callable(getattr(self._source, 'set_cli')) \
                and isinstance(source, (list, dict, tuple)):
            self._source.set_cli(source)
        return self

    def load_entries(self):
        if not isinstance(self._source, ISource):
            raise AttributeError('Unknown source for reading values. Please, set something!')
        self._entries = self._load_input(IEntry.SOURCE_GET, self._source.get()) \
            + self._load_input(IEntry.SOURCE_POST, self._source.post()) \
            + self._load_input(IEntry.SOURCE_CLI, self._source.cli()) \
            + self._load_input(IEntry.SOURCE_SESSION, self._source.session()) \
            + self._load_input(IEntry.SOURCE_FILES, self._source.files()) \
            + self._load_input(IEntry.SOURCE_ENV, self._source.env()) \
            + self._load_input(IEntry.SOURCE_SERVER, self._source.server()) \
            + self._load_input(IEntry.SOURCE_EXTERNAL, self._source.external())

    def _load_input(self, source: str, input_array=None):
        if not input_array:
            return []
        parser = self._parser_factory.get_loader(source)
        loader = self._loader_factory.get_loader(source)
        return loader.load_vars(source, parser.parse_input(input_array))

    def get_basic(self):
        return self.get_in(None, (
            IEntry.SOURCE_CLI,
            IEntry.SOURCE_GET,
            IEntry.SOURCE_POST,
        ))

    def get_system(self):
        return self.get_in(None, (
            IEntry.SOURCE_SERVER,
            IEntry.SOURCE_ENV,
        ))

    def get_cli(self):
        return self.get_in(None, IEntry.SOURCE_CLI)

    def get_get(self):
        return self.get_in(None, IEntry.SOURCE_GET)

    def get_post(self):
        return self.get_in(None, IEntry.SOURCE_POST)

    def get_session(self):
        return self.get_in(None, IEntry.SOURCE_SESSION)

    def get_files(self):
        return self.get_in(None, IEntry.SOURCE_FILES)

    def get_server(self):
        return self.get_in(None, IEntry.SOURCE_SERVER)

    def get_env(self):
        return self.get_in(None, IEntry.SOURCE_ENV)

    def get_external(self):
        return self.get_in(None, IEntry.SOURCE_EXTERNAL)

    def get_in(self, entry_key: str = None, entry_sources = None):
        for entry in self._entries:
            allowed_by_key = (not entry_key) or (entry.get_key() == entry_key)
            allowed_by_source = (not entry_sources) or (entry.get_source() in entry_sources)
            if allowed_by_key and allowed_by_source:
                yield entry

    def into_key_object_array(self, entries):
        result = []
        for entry in entries:
            result.append((entry.get_key(), entry))
        return dict(result)
